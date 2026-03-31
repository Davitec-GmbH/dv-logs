.. include:: /Includes.rst.txt

=============
Configuration
=============

.. contents:: On this page
   :local:
   :depth: 2

No configuration needed
=======================

DV Logs works out of the box. It reads log files from the standard TYPO3
log directory (``var/log/``).

Access control
==============

The module is restricted to **admin users** only. This is configured in
``Configuration/Backend/Modules.php`` via ``'access' => 'admin'``.

Log directory
=============

The extension reads files from ``Environment::getVarPath() . '/log/'``,
which is the standard TYPO3 log directory. No custom path configuration
is supported — the module always uses the TYPO3 default.
