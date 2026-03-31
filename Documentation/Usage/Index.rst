.. include:: /Includes.rst.txt

=====
Usage
=====

.. contents:: On this page
   :local:
   :depth: 2

Viewing log files
=================

1. Log in to the TYPO3 backend as an admin user
2. Navigate to :guilabel:`System > DV Logs`
3. The list view shows all log files with their size and creation date
4. Click on a log file name to view its contents

Log detail view
===============

The detail view displays parsed log entries with:

- **Date/Time** — when the entry was logged
- **Level** — error, warning, info, etc. (color-coded)
- **Message** — the log message

The most recent 500 lines are shown in reverse chronological order
(newest first).

Supported log formats
---------------------

The parser recognizes two TYPO3 log formats:

1. **Standard format**: ``Mon, 19 Aug 2024 14:52:30 +0000 [ERROR] ...``
2. **Component format**: ``component="TYPO3.CMS.Core" ... [WARNING] ...``

Lines that don't match either format are displayed as plain text.

Deleting log files
==================

Click the delete button next to a log file in the list view to remove it.
This action is immediate and cannot be undone.
