.. include:: /Includes.rst.txt

============
Introduction
============

.. contents:: On this page
   :local:
   :depth: 2

What does it do?
================

**DV Logs** is a lightweight TYPO3 backend module that provides a simple
interface for viewing and managing TYPO3 system log files directly from
the backend without SSH access.

Features
========

- Backend module under **System > DV Logs**
- Lists all log files from ``var/log/`` with size and creation date
- Detail view with parsed log entries (datetime, level, message)
- Supports standard TYPO3 log format and component-based format
- Displays the last 500 lines in reverse chronological order
- Delete log files directly from the backend
- Color-coded log levels (error, warning, info)
- TYPO3 v12 LTS, v13 LTS, and v14 support
- Admin-only access
