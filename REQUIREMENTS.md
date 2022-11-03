# CRM

## Project Goals

The goal of the system is to represent fully dynamic CRM system for customers that will be able to create & manage their business entities & data flow using visual tools that will not require client to have technical skills or developer team.

## Dictionary

- `User` - Authenticatable actor entity (Customer)
- `Tenant` - Root object that will contain all entities in scope of itself
- `Blueprint` - CRM Entity blueprint that will be a well-defined schema with unique name (within scope of tenant) & all columns with their types specified.
- `Entity` - CRM Entity that will inherit schema from corresponding blueprint and will contain all values of specified columns.
- `Flow` - Well-described flow that will contain all data transitions of entities

## Use Cases

Some common simplified use-cases of the system are listed below:

### Delivery System (Logistics)

**THE PROBLEM**: Delivery business that needs to automate, visualize the delivery flow.

**THE SOLUTION**:

1. User logins/registers into the system
2. User creates a tenant called `X Delivery System`
3. User creates a blueprint that will represent a delivery entity:

| Key          | Value                                             |
| ------------ | ------------------------------------------------- |
| id           | Unique entity identifier                          |
| deliver_from | Source destination                                |
| deliver_to   | Target destination                                |
| cargo        | Cargo description                                 |
| status       | Delivery status (PENDING, IN_PROGRESS, DELIVERED) |
| created_at   | Timestamp when the delivery was created           |
| started_at   | Timestamp when the delivery was started           |
| delivered_at | Timestamp when the delivery was completed         |

4. User creates a flow that will represent data transition

| Name             | Data updates                                                  |
| ---------------- | ------------------------------------------------------------- |
| Delivery started | status changes to IN_PROGRESS & started_at saves current time |
| Delivered        | status changes to DELIVERED & delivered_at saves current time |

5. User creates a delivery entity using the blueprint.
6. Using UI user transition the entity from `Delivery started` to `Delivered`

### Stock Market

**THE PROBLEM**: Stock that needs to automate the stock status management.

**THE SOLUTION**:

1. User logins/registers into the system
2. User creates a tenant called `X Stock`
3. User creates a blueprint that will represent a stock item:

| Key       | Value                         |
| --------- | ----------------------------- |
| id        | Unique entity identifier      |
| type      | Type of the item              |
| space     | How much space the item takes |
| weight    | Item weight                   |
| qty       | Item quantity                 |
| timestamp | Timestamp when item was kept  |

5. User creates a delivery stock item using the blueprint.
6. Using UI user manages stock items

## Functional Requirements

### User Requirements

- User must be able to login/register using email, google or facebook accounts.
- User must be able to link facebook/google social accounts
- User must be able to verify email after registration, verification email must be sent right after user has registered with email & password.
- User must be forced to enable 2FA in a month after registration, during first month user must be prompted to enable it each time logging in into system.
- User must be able to update/reset its password using email with specific reset password link.
- User must be able to contact to our support team via support email address.
- User must be able to permanently delete the account that will hard delete all the data that was related to the user.
- User must be able to create tenants (Tenant - a top level entity that will aggregate all data related to entities, flows etc). The tenant must contain name, slug (id), timezone & language
- User must be able to create entity blueprints (A schema representation of entity) in scope of tenants and specify the entity name, columns with corresponding types.
- User must be able to edit entity blueprints without affecting already existing entities.
- User must be able to migrate entities with outdated blueprint to the latest version of blueprint, the migration must support both forward & backward cases.
- User must be able to delete entity blueprints without deleting existing entities. All existing entities without corresponding blueprints must be available in trash section of the admin panel.
- User must be able to Create/Update/Delete entities with columns that are specified in the blueprint.
- User must be able to view, filter, sort entities be their schema.

### Super Admin Requirements

- Super Admin user must be created while system initial bootstrap.
- Super Admin must be able to login into system with separate dashboard login screen.
- Super Admin must be forced to update initial password, verify email & enable 2FA.
- Super Admin must be able to manage (view, update, delete) all system objects (users, tenants, entities etc).

## Non-Functional Requirements

- The system must contain fluent and intuitive UI for creating, managing and adjusting objects, their relations, different states and flows. The design must not be overcomplicated as usual CRM system and must not require any sort of guidance or trainings to use it.
- The system must not receive or send big responses, wait for long-processing requests. All long-running tasks must be handled async in background.

## Infrastructure Requirements

- The project must have two stages `development` & `production`
- Production data sources must ensure consistency, scalability & regular backups
- Consistent automated infrastructure utilities (Terraform)
- CI/CD & detailed documentation on the backend/frontend services infrastructure
- Access tokens must expire in less than a day, refresh tokens 1.5 day
- The system must report all logs & errors to appropriate services (front - sentry, back - logstash & kibana)
