# AGENDA FOR JULY 8 2025
**TO-DO**

- [ ]  Product Research
- [x]  categorize explore page (klowi)
- [ ]  UI (View Prod, Manage, BookingProcess,Login)
- [ ]  backend ng ui
- [ ]  admin panel pakitapos
- [x]  Fix header.php klo
- [x]  UI ABOUT done

# binago july 7,2025
NOTE: pakita ko july 8 pano gawin sa personal pc habang di pa naghhost
1. integrated backend database to explorepage
2. uniform header across all pages (one header.php for all)
3. added minimalistic scrollbar
  to do:
  - categorize explore page (klowi)
  - ui ng lahat 
  - backend ng ui
  - admin panel pakitapos

# Discover Manila - Admin System

This project now includes a complete admin system for managing experiences on the explore page, replacing the hardcoded content with dynamic database-driven content.

## Features

- **Admin Authentication**: Secure login system for administrators
- **Experience Management**: Add, edit, and delete experiences
- **Dynamic Content**: Explore page now displays real experiences from the database
- **Responsive Design**: Admin panel works on all devices
- **Sample Data**: Pre-loaded with sample Manila experiences

## Setup Instructions

### 1. Database Setup
1. Make sure you have XAMPP installed and running (Apache + MySQL)
2. Navigate to your project folder in the browser: `http://localhost/php-final-proj/`
3. Run the setup script: `http://localhost/php-final-proj/setup.php`
4. This will create the database and default admin user

### 2. Default Admin Credentials
- **Username**: `admin`
- **Password**: `admin123`

### 3. Access Admin Panel
- Click the "Admin" link in the header navigation
- Or go directly to: `http://localhost/php-final-proj/admin/login.php`

## Admin Features

### Dashboard (`/admin/dashboard.php`)
- View all experiences in a grid layout
- Quick actions to edit or delete experiences
- Add new experience button

### Add Experience (`/admin/add_experience.php`)
- Form to create new experiences
- Fields: Title, Description, Location, Price, Duration, Category, Available Slots, Image URL
- Categories: History, Food, Arts & Culture, Nature, Adventure, Shopping, Nightlife

### Edit Experience (`/admin/edit_experience.php`)
- Modify existing experiences
- Pre-populated form with current data
- Same fields as Add Experience

## Database Structure

### Tables Created:
- **Admin**: Admin user credentials
- **Users**: Regular user accounts (for future use)
- **Experiences**: Experience data (title, description, location, price, etc.)
- **Experience_Schedule**: Scheduling information (for future use)
- **Bookings**: Booking records (for future use)

### Sample Experiences Included:
1. **Intramuros Heritage Walk** - Historic tour of Manila's walled city
2. **Binondo Food Crawl** - Chinese-Filipino cuisine exploration
3. **Rizal Park Sunset Tour** - Scenic park tour

## File Structure

```
php-final-proj/
├── admin/
│   ├── login.php          # Admin login page
│   ├── dashboard.php      # Main admin dashboard
│   ├── add_experience.php # Add new experience form
│   ├── edit_experience.php # Edit experience form
│   ├── logout.php         # Logout functionality
│   └── auth_check.php     # Authentication middleware
├── includes/
│   ├── db_connect.php     # Database connection
│   ├── header.php         # Site header (updated with admin link)
│   └── footer.php         # Site footer
├── assets/
│   └── css/
│       └── style.css      # Updated with experience card styles
├── index.php              # Updated to fetch from database
├── setup.php              # Database initialization script
└── README.md              # This file
```

## Security Features

- Password hashing using PHP's `password_hash()`
- Session-based authentication
- SQL injection prevention with prepared statements
- XSS prevention with `htmlspecialchars()`
- Authentication checks on all admin pages

## Customization

### Adding New Categories
Edit the category dropdown in `add_experience.php` and `edit_experience.php` to add new experience categories.

### Styling
Modify `assets/css/style.css` to customize the appearance of experience cards and admin panels.

### Database Configuration
Update `includes/db_connect.php` if you need to change database connection settings.

## Troubleshooting

### Database Connection Issues
- Ensure XAMPP MySQL service is running
- Check database credentials in `includes/db_connect.php`
- Verify database name is `manila_experiences`

### Admin Login Issues
- Run `setup.php` to create the default admin user
- Check that the Admin table was created properly
- Verify password hashing is working

### Experience Display Issues
- Check if experiences exist in the database
- Verify the database connection in `index.php`
- Check for PHP errors in the browser console

## Future Enhancements

- Image upload functionality
- Experience scheduling system
- User booking system
- Advanced search and filtering
- Analytics dashboard
- Email notifications

# Admin Panel for Discover Manila

## Features
- Secure admin login (not visible on public site)
- Dashboard with stats
- Manage Experiences (add, edit, delete)
- Flash messages for feedback
- Sidebar navigation

## How to Access
- The admin panel is only accessible via direct link: `/admin/login.php`
- There are no links to admin in the public site navigation.

## Setup
1. Make sure your MySQL server is running and the `manila_experiences` database exists.
2. Create an admin user in the `Admin` table (with a hashed password):
   - You can use PHP's `password_hash('yourpassword', PASSWORD_DEFAULT)` to generate the hash.
3. Visit `/admin/login.php` to log in.

## File Structure
```
admin/
  auth.php
  login.php
  logout.php
  dashboard.php
  experiences.php
  admin.css
includes/
  db_connect.php
README.md
```

## Security
- Only accessible via direct URL
- Session-based authentication
- No public links to admin 