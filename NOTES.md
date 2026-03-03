# NOTES: Document Versioning API

## Approach

* Implemented a single store endpoint in DocumentController to handle both creating a new document and updating an existing one.

* Versioning logic:

  * When a document is created, version 1 is added automatically.
  * When a document is updated, a new version is created with version_number = latest + 1.

* Used Laravel relationships:

  * Document → hasMany → DocumentVersion
  * Document → latest version stored via version_id for quick retrieval.

* Validation ensures:

  * title and slug are required
  * slug is unique

* Feature tests (DocumentApiTest) demonstrate:

  * Creating a document creates version 1
  * Updating a document increments the version number

## Trade-offs 

* No authentication/authorization included; assumes trusted usage.
* Current design uses string content instead of actual files to simplify versioning and focus on behavior.
* RefreshDatabase in tests ensures isolation, but slows down the test run slightly

## Future Improvements

* Add pagination or filtering for document and version lists
* Introduce soft deletes
* Add user-specific scoping for multi-user scenarios