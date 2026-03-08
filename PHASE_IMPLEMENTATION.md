# PMS Phase 1–3 Implementation (Backend)

This repository now includes a unified backend implementation for all requested Phase 1, Phase 2, and Phase 3 features.

## Implemented Foundation

- **Schema expansion** for land sales, accrual accounting, commissions, overpayments, credits, utilities, assets, depreciation, email threads, reminders, multi-currency, branches/projects/blocks/plots, and portal feedback.
- **Business logic service** in `app/Services/UnifiedPmsService.php` for:
  - sale creation and financial recalculation
  - overpayment detection + customer credit creation
  - accrual entries
  - configurable commission triggering
  - control number generation
  - asset depreciation
  - automated reminder generation
  - consolidated KPI/report summary
- **API controller** in `app/Http/Controllers/Api/UnifiedPmsController.php`.
- **API routes** under `routes/api.php` with prefix `/api/v1/...`.
- **Scheduler command** `pms:generate-reminders` (daily at 07:00).
- **Feature tests** for core financial operations.

## Main API Endpoints

### Master data
- `POST /api/v1/branches`
- `POST /api/v1/projects`
- `POST /api/v1/blocks`
- `POST /api/v1/plots`
- `POST /api/v1/customers`
- `POST /api/v1/sellers`
- `POST /api/v1/agents`

### Sales, commissions, overpayment, accrual
- `POST /api/v1/sales`
- `POST /api/v1/sales/{saleId}/charges`
- `POST /api/v1/sales/{saleId}/payments`
- `POST /api/v1/sales/{saleId}/status`
- `GET /api/v1/sales/{saleId}/statement`
- `POST /api/v1/control-numbers`

### Portal & communication
- `GET /api/v1/portal/listings`
- `POST /api/v1/portal/feedback`
- `POST /api/v1/email/threads`
- `POST /api/v1/email/threads/{threadId}/messages`
- `GET /api/v1/email/threads/{threadId}`

### Charges, maintenance, assets
- `POST /api/v1/utility-bills`
- `POST /api/v1/utility-bills/{billId}/mark-paid`
- `POST /api/v1/maintenance-schedules`
- `POST /api/v1/assets`
- `POST /api/v1/assets/{assetId}/depreciate`

### Reporting, currency, notifications
- `POST /api/v1/currency-rates`
- `GET /api/v1/reports/currency-valuation`
- `GET /api/v1/reports/summary`
- `POST /api/v1/notifications/reminders/generate`
- `POST /api/v1/notifications`

## Notes

- Existing rental/property modules remain intact.
- New implementation is additive and designed to integrate with existing UI/workflows.
- If needed, a second step can expose these APIs in Blade screens and permission-gated menus.
