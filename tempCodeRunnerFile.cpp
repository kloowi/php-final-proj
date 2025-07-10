#include <iostream>
#include <thread>
#include <queue>
#include <mutex>
#include <condition_variable>
#include <string>
#include <chrono>

std::queue<std::string> data_queue;
std::mutex mtx;
std::condition_variable cv;
bool done = false;

const int NUM_ITEMS = 20;

void producer() {
    for (int i = 0; i < NUM_ITEMS; ++i) {
        std::this_thread::sleep_for(std::chrono::milliseconds(100)); // Simulate delay
        std::string item = "Item–" + std::to_string(i); // en dash like screenshot

        {
            std::lock_guard<std::mutex> lock(mtx);
            data_queue.push(item);
            std::cout << "Producer: Produced " << item << std::endl;
        }

        cv.notify_one();
    }

    {
        std::lock_guard<std::mutex> lock(mtx);
        done = true;
    }

    cv.notify_one(); // Notify consumer to exit
}

void consumer() {
    while (true) {
        std::unique_lock<std::mutex> lock(mtx);
        cv.wait(lock, [] { return !data_queue.empty() || done; });

        while (!data_queue.empty()) {
            std::string item = data_queue.front();
            data_queue.pop();

            lock.unlock(); // Unlock while processing

            std::this_thread::sleep_for(std::chrono::milliseconds(80)); // Simulate processing
            std::cout << "Consumer: Consumed " << item << std::endl;

            lock.lock();
        }

        if (done && data_queue.empty()) {
            break;
        }
    }

    std::cout << "Consumer: Finished consuming" << std::endl;
}

int main() {
    std::thread t1(producer);
    std::thread t2(consumer);

    t1.join();
    t2.join();

    std::cout << "Main thread: All done." << std::endl;
    return 0;
}
