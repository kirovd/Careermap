# Careermap

## Introduction
Careermap is a comprehensive web application that facilitates the job search and application process. This application allows users to create, view, and accept job listings, providing a user-friendly and efficient platform for career advancement.

## Getting Started
To set up and run the application locally, please follow these steps:

1. Clone this repository to your local machine.
2. Run the command ``php artisan migration`` to initialize the database and create the necessary job table.
3. Launch the server by running ``php artisan serve``.

## Creating a Job
To create a job listing, users are required to provide a job title, along with a detailed description of the position. Upon completing the form, users can submit it, triggering a confirmation message using Sweet Alert (Swal) for validation purposes.

## List of Jobs
The application displays a comprehensive list of available jobs in an organized manner. Each job listing includes the job title only, allowing users to quickly browse through the available options.

## Viewing and Accepting a Job

By clicking on a specific job from the list, users can view a detailed job description. The description includes the job title, creation date, description, and status. Sweet Alert (Swal) is utilized to present this information in an appealing and user-friendly manner. Two buttons are provided: one for accepting the job and another for canceling the action. When a user accepts a job, a badge indicating acceptance is displayed alongside the job title. If users wish to view the job details again, they can do so by clicking on the job, and an "OK" button is provided to close the detailed view.

Feel free to explore Careermap and make the most of its features!
