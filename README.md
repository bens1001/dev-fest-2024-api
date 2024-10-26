<h1 align="center">Welcome to Smart Analytics Platform for Car Manufacturing Operations 👋</h1>
<p>
  <a href="https://app.swaggerhub.com/apis/ABDELHAMIDBENSALAH35/dev-fest_2024_2nd_challenge_api_documentation/1.0.1" target="_blank">
    <img alt="Documentation" src="https://img.shields.io/badge/documentation-yes-brightgreen.svg" />
  </a>
</p>

> This project is a smart analytics platform designed for car manufacturing operations. It provides real-time tracking of machine performance, production outputs, energy consumption, and defect management. The platform also integrates with factory equipment such as stamping presses, welding robots, and AGVs to ensure continuous operation, optimize energy usage, and maintain high-quality production standards.

## Features

### AI Data Point Integration
- **Anomaly Detection**: Optional parameters to retrieve data points for specific machines and analyze them through an integrated AI model to identify anomalies.
- **Data Processing**: Each data point can trigger alerts if anomalies are detected, improving operational efficiency.

### Real-Time Data Processing
- **Sensor Integration**: The API integrates with a simulated server that sends real-time sensor data every 20 seconds, allowing continuous monitoring of machine performance.
- **WebSocket Communication**: Utilizes Reverb for WebSocket communication, enabling real-time updates and event-driven architecture.

### Defect Management
- **Create Defects**: Allows users to log defects related to machines via a `POST` request, including necessary details such as defect type and machine ID.
- **List Defects**: Retrieve a list of all recorded defects through a `GET` request, with optional filters for specific machines or defect types.
- **Update Defects**: Update existing defects with a `PUT` request to modify details like defect type or status.
- **Delete Defects**: Remove defects from the system using a `DELETE` request.

### User Management
- **Role-Based Access Control**: Implements user roles (admin, manager, opertator) to ensure proper permissions for different actions, such as creating users or managing defects.
- **User Creation**: Admin users can create new accounts via a `POST` request.
- **User Listing**: Retrieve all users in the system using a `GET` request.

### Energy Usage Tracking
- **Monitor Energy Consumption**: The API tracks energy usage per machine, allowing manufacturers to optimize efficiency.
- **Log Energy Data**: Log energy usage records for machines using a `POST` request, including fields for consumed energy and timestamps.

### Search Functionality
- **Dynamic Search**: Optional search parameters in the `GET` requests for machines, defects, and alerts allow users to filter results based on specific criteria.


### Enhanced Documentation
- **Scribe Integration**: Automatically generated API documentation using the Scribe package. This includes detailed information on endpoints, request/response formats, and usage examples.


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
./vendor/bin/sail php artisan migrate
```

## API Documentation

Full API documentation is available on **SwaggerHub**:
- [SwaggerHub API Documentation](https://app.swaggerhub.com/apis/ABDELHAMIDBENSALAH35/dev-fest_2024_2nd_challenge_api_documentation/1.0.1)

- ![image](https://github.com/user-attachments/assets/a06e97d9-1427-4a04-af40-734e5ec93dd2)

### Generating Documentation with Scribe

Since the Scribe package is already installed and configured, you can generate the API documentation using the following command:

```sh
php artisan scribe:generate
```

You can view it by navigating to `http://your-app-url/docs` in your web browser.

### OpenAPI and Postman Collection

- **OpenAPI Specification**:
  You can access the OpenAPI specification in JSON format at the following URL:

  ```
  http://your-app-url/docs/openapi.json
  ```

- **Postman Collection**:
  To use the Postman collection, you can import the following URL into Postman:

  ```
  http://your-app-url/docs/postman_collection.json
  ```

### API Overview

### Endpoints

#### 1. Data Points
- **Retrieve Data Points**
  - `GET /data-points`
    - **Query Parameters**: 
      - `machine_id` (optional): Filter data points by machine ID.
      - `search` (optional): Search term for data point attributes.
    - **Response**: Returns a list of data points with timestamps, KPI values, and related machine information.

- **Get Data Points by Machine**
  - `GET /data-points/machine/{machine_id}`
    - **Response**: Returns data points specific to the given machine ID.

#### 2. Defects
- **Create a Defect**
  - `POST /defects`
    - **Body**: 
      ```json
      {
        "machine_id": "string",
        "defect_type": "string",
        "defect_time": "timestamp"
      }
      ```
    - **Response**: Returns the created defect details.

- **List Defects**
  - `GET /defects`
    - **Query Parameters**:
      - `machine_id` (optional): Filter defects by machine ID.
      - `defect_type` (optional): Filter by defect type.
    - **Response**: Returns a list of defects.

- **Get Defect by ID**
  - `GET /defects/{id}`
    - **Response**: Returns details of a specific defect.

- **Update a Defect**
  - `PUT /defects/{id}`
    - **Body**: 
      ```json
      {
        "defect_type": "string",
        "defect_time": "timestamp"
      }
      ```
    - **Response**: Returns the updated defect details.

- **Delete a Defect**
  - `DELETE /defects/{id}`
    - **Response**: Confirms deletion of the defect.

#### 3. Alerts
- **Retrieve Alerts**
  - `GET /alerts`
    - **Query Parameters**:
      - `machine_id` (optional): Filter alerts by machine ID.
    - **Response**: Returns a list of generated alerts based on defect detection.

#### 4. Energy Usage
- **Log Energy Usage**
  - `POST /energy-usage`
    - **Body**: 
      ```json
      {
        "machine_id": "string",
        "energy_consumed": "number",
        "start_shift_time": "timestamp",
        "end_shift_time": "timestamp"
      }
      ```
    - **Response**: Returns the logged energy usage details.

- **Retrieve Energy Usage**
  - `GET /energy-usage`
    - **Response**: Returns energy usage records for machines.

#### 5. Users
- **List Users**
  - `GET /users`
    - **Response**: Returns a list of users in the system (admin access required).

- **Create User**
  - `POST /users`
    - **Body**: 
      ```json
      {
        "name": "string",
        "email": "string",
        "role": "string"
      }
      ```
    - **Response**: Returns the created user details.

## Usage Examples

Start your local server using Laravel Sail:

```sh
./vendor/bin/sail up
```

To generate an application key:

```sh
./vendor/bin/sail php artisan key:generate
```

To run database migrations:

```sh
./vendor/bin/sail php artisan migrate
```

## Author

- Github: [@bens1001](https://github.com/bens1001)
- LinkedIn: [@abdelhamid-bensalah](https://linkedin.com/in/abdelhamid-bensalah)

## Show Your Support

Give a ⭐️ if this project helped you!

