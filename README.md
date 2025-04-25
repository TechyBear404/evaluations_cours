# 📝 IfoForm

IfoForm is a web application developed as part of a school project. It allows school administrators to send satisfaction surveys to students after attending a course.

<img src="https://github.com/TechyBear404/evaluations_cours/blob/main/public/images/screenshot/courses_listing.png?raw=true" alt="courses listing" width="300" />
<img src="https://github.com/TechyBear404/evaluations_cours/blob/main/public/images/screenshot/create_form.png?raw=true" alt="create form" width="300" />
<img src="https://github.com/TechyBear404/evaluations_cours/blob/main/public/images/screenshot/form_student.png?raw=true" alt="form student" width="300" />
<img src="https://github.com/TechyBear404/evaluations_cours/blob/main/public/images/screenshot/survey.png?raw=true" alt="survey" width="300" />

## 🎯 Purpose

The main goal of this application is to automate the collection of student feedback, make it easier to analyze, and enable fast and efficient delivery of results to teachers.

## 🛠️ Main Features

-   Send customized questionnaires to students of a specific course.
-   Securely collect and store responses.
-   Automatically generate a summary of the feedback.
-   Export the summary in multiple formats: PDF, Word, Excel.
-   Send the summary directly via email to the relevant teacher.

## 🧰 Technologies Used

-   **Frontend**: Vue.js, Tailwind CSS, Schadcn-vue, FontAwesome
-   **Backend**: Laravel 11, Sqlite

## ⚙️ Installation

### 🔧 Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js and NPM

### 📦 Installation Steps

1. Clone this repository:

    ```bash
    git clone https://github.com/TechyBear404/evaluations_cours.git
    ```

2. Install PHP dependencies:

    ```bash
    cd evaluations_cours
    composer install
    ```

3. Install Node.js dependencies:

    ```bash
    npm install
    ```

4. Copy the .env.example file to create your .env file:

    ```bash
    cp .env.example .env
    ```

5. Configure your `.env` file with your **database**, **Admin Login** and **mailing** settings.

6. Generate the Laravel application key:

    ```bash
    php artisan key:generate
    ```

7. Run the migrations to set up the database tables:

    ```bash
    php artisan migrate --seed
    ```

    > 💡 If you want fake data, go to `database/seeders/DatabaseSeeder.php` and uncomment all factories.

8. Start backend and frontend development server:

    ```bash
    npm run dev:all
    ```

9. Access the app with the provided URL.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.

## 👤 Author

-   [Hadrien Janssens](https://github.com/Hadrien-Janssens)
-   [Techybear](https://github.com/TechyBear404)
