<h1 align="center">Welcome to Smart Analytics Platform for Car Manufacturing Operations 👋</h1>
<p>
  <a href="https://app.swaggerhub.com/apis/ABDELHAMIDBENSALAH35/dev-fest_2024_2nd_challenge_api_documentation/1.0.0" target="_blank">
    <img alt="Documentation" src="https://img.shields.io/badge/documentation-yes-brightgreen.svg" />
  </a>
</p>

> This project is a smart analytics platform designed for car manufacturing operations. It provides real-time tracking of machine performance, production outputs, energy consumption, and defect management. The platform also integrates with factory equipment such as stamping presses, welding robots, and AGVs to ensure continuous operation, optimize energy usage, and maintain high-quality production standards.

## Features

- **Real-Time Monitoring:** Tracks machine performance, production status, and energy usage in real time.
- **Defect Management:** Logs and tracks machine defects for quality control.
- **Energy Usage Tracking:** Monitors energy consumption to help optimize factory efficiency.
- **Task Scheduling:** Automates tasks like maintenance based on machine usage.
- **Dashboard & Visualization:** Interactive dashboard for factory managers to visualize data trends and machine status.
- **RESTful API Integration:** Provides APIs for managing machines, production, defects, and energy usage.
- **Authentication & Authorization:** Secure API with token-based authentication.

## Installation

To install the project, clone the repository and run the following commands:

```sh
git clone https://github.com/bens1001/dev-fest-2024-api.git
cd dev-fest-2024-api
composer install
```

## Running the Project

You can use Laravel Sail with Docker to run the application. Start the containers with the following command:

```sh
./vendor/bin/sail up
```

Then, run the database migrations to set up the schema:

```sh
./vendor/bin/sail artisan migrate
```

## API Documentation

Full API documentation is available on **SwaggerHub**:
- [SwaggerHub API Documentation](https://app.swaggerhub.com/apis/ABDELHAMIDBENSALAH35/dev-fest_2024_2nd_challenge_api_documentation/1.0.0)

### API Overview

The API allows users to manage machines, monitor production, track energy usage, handle defects, and manage sensor data.

- **Machines API**: Create, update, list, and delete machines.
- **Production API**: Manage production records, including start and end times.
- **Defects API**: Log and track defects on machines.
- **Energy Usage API**: Monitor energy consumption per machine.
- **Sensor Data API**: Collect real-time data from connected machines.

## Usage Examples

Start your local server using Laravel Sail:

```sh
./vendor/bin/sail up
```

To generate an application key:

```sh
./vendor/bin/sail artisan key:generate
```

To run database migrations:

```sh
./vendor/bin/sail artisan migrate
```

## Author

- Github: [@bens1001](https://github.com/bens1001)
- LinkedIn: [@abdelhamid-bensalah](https://linkedin.com/in/abdelhamid-bensalah)

## Show Your Support

Give a ⭐️ if this project helped you!

