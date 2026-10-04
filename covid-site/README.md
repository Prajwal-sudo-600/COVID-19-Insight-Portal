# COVID-19 Information Website

A dynamic website showing COVID-19 information, built for a Software Testing and Quality Assurance mini-project.

## Features
- Home page with summary statistics
- Data page with sortable/searchable table and HTML canvas bar chart
- User accounts (Sign Up / Login / Logout)
- Comment section (Requires login to post)
- Vaccination Registration form
- Input validation using Regular Expressions (Client-side JS and Server-side PHP)

## Requirements
- XAMPP (Apache + MySQL)

## Setup Instructions
1. Clone or copy this repository into your XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\covid-site`).
2. Start Apache and MySQL via the XAMPP Control Panel.
3. Import the database schema and sample data:
   - Open phpMyAdmin (`http://localhost/phpmyadmin`).
   - Create a database named `covid_db` (or just run the `schema.sql` file which creates it automatically).
   - Import the `sql/schema.sql` file.
   - For the sample data, run this SQL command in phpMyAdmin (adjust the path to your CSV file):
     ```sql
     LOAD DATA INFILE 'C:/xampp/htdocs/covid-site/sql/covid_data.csv'
     INTO TABLE covid_data
     FIELDS TERMINATED BY ','
     LINES TERMINATED BY '\n'
     IGNORE 1 ROWS
     (country, date, new_cases, total_cases, new_deaths, total_deaths, total_vaccinations);
     ```
4. Access the website at `http://localhost/covid-site/`.

## Testing
- Client-side Regex Tests: Open `tests/regex_tests.html` in your browser.
- Server-side Regex Tests: Open `tests/regex_tests.php` via XAMPP (`http://localhost/covid-site/tests/regex_tests.php`).
- The documented test cases can be found in `docs/test_cases.md`.
