# Employify

Employify is a job-seeking platform designed to connect job seekers with employers in a seamless and efficient manner. The project was developed collaboratively by a team of three members for their Database Systems Course at BRAC University.

## Project Members

1. **Maliha Rahman**  
   GitHub: [MALIHA022](https://github.com/MALIHA022)

2. **Md. Saadat Rahman**  
   GitHub: [MD-SAADAT-RAHMAN](https://github.com/MD-SAADAT-RAHMAN)

3. **Mohammad Jabir Safa Khandoker**  
   GitHub: [safa-jsk](https://github.com/safa-jsk)

## Features

- **User Roles:**
  - Job Seekers can search for jobs, apply for jobs, and bookmark jobs.
  - Employers can post jobs, manage applications, and view applicants.
  - Admin can delete any Job Seeker, Recruiter or Job Application.

- **Search:**
  - Search by various criteria such as job name, company, field, or salary.
  - Search all candidates by their Skills, Education or Experience.
  - Filter applied applicants based on which job they applied to and their Skills, Education or Experience. 

- **User Authentication:**
  - Secure login and registration for job seekers and employers.
  - In case of accidentally returning to the home page, no need to login once again.
  - Simplified Account update page including changing the password even in case of losing it.

- **Dynamic Application Tracking:**
  - Job seekers can see the status of their applications (applied/bookmarked).
  - Job seekers can see if theri applications have been Accepted, Rejected or ON-Hold.

- **Shortlisting**
  - Employers can Shortlist or Reject the applied applicants and can filter them based on Skills, Education or Experience.
  - Shorlisted applicants can then be either Accepted or Rejected after viewing their full profile with a dynamic popup.

- **Feedback System**
  -Anyone can submit a feedback that will be directly sent to the Admin panel to review.

## Technology Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/safa-jsk/Employify
   ```

2. Import the database:
   - Locate the `employify.sql` file in the repository.
   - Use a tool like phpMyAdmin to import the database into your MySQL server.

3. Configure the database connection:
   - Update the `DBconnect.php` file with your database credentials:
     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "employify";
     ```

4. Run the application:
   - Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Start your web server and navigate to `http://localhost/employify`.

## Usage

### Job Seekers
- **Search Jobs:**
  - Use the search bar and filters to find jobs matching your criteria.
- **Apply for Jobs:**
  - Click the "Apply" button to submit an application.
- **Bookmark Jobs:**
  - Bookmark jobs for later consideration.

### Employers
- **Post Jobs:**
  - Add new job listings with details like title, description, and salary.
- **Manage Applications:**
  - View and manage job applications from seekers.

## Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository.
2. Create a new branch for your feature/fix.
3. Submit a pull request.

## License

This project is licensed under the MIT License.

## Contact

For any inquiries or feedback, please reach out to the project members via their GitHub profiles linked above.

