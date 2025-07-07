# Step Into Manila - Admin Panel

This is the administrative panel for the Step Into Manila website, allowing administrators to manage experiences, bookings, announcements, and schedules.

## Features

- **Dashboard**: Overview of key statistics and recent activities
- **Announcements**: Create, edit, and manage site announcements
- **Appointments**: View and manage customer bookings
- **Services**: Manage experience offerings
- **Schedules**: Manage experience schedules and availability
- **User Management**: Admin user management (super admin only)

## Installation

1. Ensure the database is set up with the schema from `../database/schema.sql`
2. Verify database connection settings in `../includes/db_connect.php`
3. Access the admin panel at `your-domain.com/admin/`

## Default Login Credentials

- **Username**: `admin`
- **Password**: `admin123`

**Important**: Change these credentials after first login for security!

## File Structure

```
admin/
├── auth.php              # Authentication and session management
├── login.php             # Admin login page
├── dashboard.php         # Main dashboard
├── announcements.php     # Announcements management
├── appointments.php      # Bookings/appointments management
├── services.php          # Experiences management
├── schedules.php         # Schedule management
├── logout.php            # Logout handler
├── admin.css             # Admin panel styles
├── test_admin.php        # Test file for debugging
└── README.md             # This file
```

## Database Tables

The admin panel uses the following database tables:

- `Admin` - Admin user accounts
- `admin_sessions` - Admin session management
- `Users` - Customer accounts
- `Experiences` - Experience offerings
- `Bookings` - Customer bookings/appointments
- `Experience_Schedule` - Experience schedules
- `Announcements` - Site announcements
- `Payment` - Payment records

## Security Features

- Session-based authentication
- CSRF protection
- Input validation and sanitization
- Role-based access control
- Secure password hashing
- Session timeout (24 hours)

## Usage

### Dashboard
- View key statistics (total bookings, pending bookings, etc.)
- See recent activities
- Quick access to all management functions

### Announcements
- Create new announcements
- Edit existing announcements
- Mark announcements as featured
- Activate/deactivate announcements
- Delete announcements

### Appointments
- View all customer bookings
- Filter by status (pending, confirmed, completed, cancelled)
- Update booking status
- View customer details

### Services
- View all experience offerings
- See experience details (price, location, duration)
- Manage experience status

### Schedules
- View experience schedules
- See booking capacity and availability
- Manage schedule details

## Troubleshooting

1. **Database Connection Issues**: Check `../includes/db_connect.php` settings
2. **Login Problems**: Verify admin credentials in database
3. **Missing Tables**: Run the database schema from `../database/schema.sql`
4. **Test Functionality**: Use `test_admin.php` to verify all features

## Customization

- Modify `admin.css` for styling changes
- Update navigation in `auth.php` (getAdminNavigation function)
- Add new features by extending the admin functions in `../includes/admin_functions.php`

## Support

For issues or questions, check the test file (`test_admin.php`) to verify functionality and database connectivity. 