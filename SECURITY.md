# Security Policy

PHP-SBCS is a learning/demo repository. It includes basic safeguards, but it is not intended to be used as a production ecommerce system without additional hardening.

## Supported Versions

Security fixes are considered for the latest commit on the default branch.

## Reporting a Vulnerability

Please do not open a public issue for sensitive vulnerabilities.

If you find a security issue, contact the maintainer through the profile or repository contact channels and include:

- A short description of the issue
- Steps to reproduce
- Affected file or feature
- Possible impact
- Suggested fix, if available

## Known Production Gaps

- Order data can contain personal information and should not be stored under a public web root in production.
- `.htaccess` protections only apply to Apache.
- Authentication, authorization, rate limiting, audit logs, and centralized monitoring are not included.
- Verbose error reporting should be disabled in production.
- Payment options are demo labels, not real payment gateway integrations.
