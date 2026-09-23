# CodeIgniter 4 Sandbox & Experiments

A hands-on repository exploring the core capabilities, security features, and API architecture of CodeIgniter 4.

### Features & Concepts Explored

* **Core MVC Architecture:** Custom Routing, 404 handling, and basic CRUD operations.
* **Content Management (Blog Engine):**
  * Dynamic post creation and editing powered by the **Quill Rich Text Editor**.
  * Server-side HTML sanitization using `ezyang/htmlpurifier` to secure rich content against Stored XSS before database insertion.
  * Native server-side pagination with custom layout styling aligned via CSS Flexbox (`gap` and right-alignment).
* **User Identity & Account Management:** 
  * Secure User Registration (Signup) and Authentication (Login) workflows.
  * Profile management and account details edition.
  * Access control and route protection managed via CI4 Filters.
* **Data Validation:** Implementation of custom validation rules.
* **Application Security:** 
  * CSRF token protection for non-GET requests.
  * Anti-spam Honeypot fields.
  * Input XSS sanitization filters.
* **Hybrid API Authentication Architecture (Custom Before Filter):**
  * **External Consumption:** Stateless JWT token validation using the `firebase/php-jwt` library, delivering structured JSON payloads equipped with standard pagination metadata.
  * **Internal Consumption:** Stateful Session-based validation coupled with strict CSRF enforcement for mutation endpoints.
* **Unit Tests:** Implementation of some unit and integration tests through PHPUnit.