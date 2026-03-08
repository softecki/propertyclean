# Property Management System — Product Overview & Roadmap

**Document for Customer**  
*Land Property Selling • Property Management • Real Estate — Tanzania Version*

---

## Executive Summary

**Great** — since you're building a Property Management System, this document sets out a **professional, standard feature structure** you can confidently present to clients (especially real estate companies managing land, plots, rentals, agents, commissions, excess payments, accruals, etc.). It is organized in **Core Modules → Sub-features** so it can be translated directly into database tables and system modules.

This Property Management System (PMS) is designed to support **two core business streams**:

1. **Land property selling** — Plot/block management, sales agreements, installments, agent commissions, and excess payment handling.  
2. **Property management** — Rental properties, tenants, contracts, invoices, maintenance, and expenses.

The system is built on **Laravel** with role-based access, and can be positioned as a **Real Estate Management System – Tanzania Version** for land developers, property companies, and real estate agents.

**Agent & commission fees and percentages are fully configurable** — since they differ by client, the system allows each client (or project) to define their own commission rules: percentage of sale, fixed amount, when commission is due (on agreement, on full payment, or on collected amount), and any other fee structures. No hard-coded rates.

---

## 1. Features Currently Available (Existing System)

The following modules are **already implemented** in the current application:

| Module | Description | Status |
|--------|-------------|--------|
| **Property management** | Properties (own/lease), locations (country, state, city, address), images, active/inactive | ✅ Available |
| **Property units** | Units per property (e.g. rooms, beds), linked to properties | ✅ Available |
| **Tenant management** | Tenant profiles, documents, assignment to units | ✅ Available |
| **Contracts** | Contract creation and management linked to tenants/properties | ✅ Available |
| **Invoicing** | Invoices per unit, invoice items, open/partial/paid status | ✅ Available |
| **Payments** | Invoice payments (bank transfer, Stripe, PayPal), receipt handling | ✅ Available |
| **Expenses** | Expense tracking and management | ✅ Available |
| **Maintenance** | Maintainer profiles and maintenance requests (pending, in progress, etc.) | ✅ Available |
| **Types** | Custom types (e.g. property/unit classifications) | ✅ Available |
| **Users & roles** | User management with role and permission control | ✅ Available |
| **Notice board / Notes** | Internal notes and notice board | ✅ Available |
| **Contacts** | Contact management | ✅ Available |
| **Subscriptions** | Subscription plans, Stripe/PayPal, bank transfer | ✅ Available |
| **Coupons** | Coupon management and history | ✅ Available |
| **Settings** | Account, password, general, SMTP, payment, company, SEO, reCAPTCHA | ✅ Available |
| **Audit** | Logged user history | ✅ Available |
| **Dashboard** | Home/dashboard view | ✅ Available |

**Technology in use:** Laravel, Blade, AJAX, MySQL, role-based access, XSS protection, multi-payment (Stripe, PayPal, bank transfer).

---

## 2. Features Needed for Land Selling & Full PMS (Planned)

To fully support **land property selling** and **accrual-based accounting**, the following capabilities are **planned or required**:

| # | Feature | Description | Priority |
|---|--------|-------------|----------|
| 1 | **Statement of excess payment** | Detect overpayment, show excess balance, generate PDF/print statement, and allow: Refund / Carry forward to next property / Keep as credit | High |
| 2 | **Accrual accounting** | Income when land is sold (even if not fully paid), commission when sale is confirmed, outstanding balances, Accounts Receivable, Deferred Income, Commission Payable, revenue recognition | High |
| 3 | **Agent commission (on sold land)** | Assign agent per sale, commission type (% or fixed), auto-calculation on sale confirmation or full payment, unpaid commission tracking, commission report per agent, payment history | High |
| 4 | **Extra payments from seller amount** | Add extra charges (survey, title, transfer, legal, penalty, documentation), auto-update buyer balance, audit trail (who, when, reason) | High |
| 5 | **Land/plot structure** | Project → Block → Plot (number, size, price), status: Available / Reserved / Sold / Cancelled | High |
| 6 | **Buyer management** | Buyer profile, ID, phone, address, assigned plots, payment history, statement generation | High |
| 7 | **Sales module** | Sale agreement, plot assigned, sale price, agent, commission %, payment plan, extra charges | High |
| 8 | **Payment module (land)** | Record payment, method, receipt number, auto balance update, overpayment detection, excess statement | High |
| 9 | **Accounting module (accrual)** | Tables: sales, payments, receivables, commissions, optional journal/expense entries | Medium |
| 10 | **Agent module** | Agent profile, commission setup, status: Pending / Approved / Paid, commission statement | High |
| 11 | **Reports** | Sales report, agent commission report, overpayment report, outstanding balances, cash flow, profit per project | Medium |
| 12 | **Multi-project / multi-branch** | Support multiple projects and branches if required by client | To clarify |
| 13 | **Email system (inbox & reply)** | Receive emails into the system (e.g. support@ or property inbox); users view and reply to emails from within the system; keep thread history and audit | High |
| 14 | **WhatsApp / SMS** | Notifications (e.g. payment reminders, receipts) | Future |

---

## 3. World-Standard PMS Modules (Core → Sub-features)

The following structure is a **professional, standard feature set** for a world-class Property Management System. Each module is broken into sub-features for direct translation into database tables and system screens.

### 3A. Property Management Module

| Sub-feature | Description |
|-------------|-------------|
| **A. Property Registration** | Add property (Land / House / Apartment / Commercial). Property code / plot number. Location (Region, District, GPS optional). Property size (sqm / acres). Ownership status. Selling price. Rental price. Property documents upload (title deed, contracts). |
| **B. Property Status** | Available • Reserved • Sold • Rented • Under Maintenance |

### 3B. Customer & Seller Management

| Sub-feature | Description |
|-------------|-------------|
| **A. Buyer / Tenant Management** | Full name, phone, email, ID number, address. Customer type (Buyer / Tenant / Investor). Customer statement (all payments history). Excess payment tracking. Outstanding balance tracking. |
| **B. Seller Management** | Seller profile. Land ownership details. Agreed selling price. Amount paid to seller. Remaining seller balance. Extra payments added from seller side. |

### 3C. Sales & Payment Management

| Sub-feature | Description |
|-------------|-------------|
| **A. Sales Management** | Create sale contract. Assign property to buyer. Payment plan (installments). Down payment tracking. Auto-generate agreement document. |
| **B. Payment Tracking** | Record payment (Cash / Bank / Mobile Money). Issue receipt. Excess payment detection. Auto balance update. Penalty calculation (late payment). Accrual accounting support. |
| **C. Statement Management** | Customer statement. Seller statement. Agent commission statement. |

### 3D. Accounting & Financial Control

| Sub-feature | Description |
|-------------|-------------|
| **A. Income Tracking** | Property sales income. Rental income. Penalties. Other charges. |
| **B. Expense Management** | Maintenance cost. Agent commission. Office expenses. Land processing cost. |
| **C. Accrual System** | Record revenue when sale is made. Track unpaid balances. Deferred income tracking. Profit per property report. |

### 3E. Agent & Commission Management *(Fees & percentages configurable per client)*

| Sub-feature | Description |
|-------------|-------------|
| **A. Agent Profiles** | Name, phone. **Configurable commission percentage or fixed amount per agent/client.** Assigned properties. |
| **B. Commission Calculation** | Auto-calculate based on: **percentage of sale** or **fixed amount** (both configurable). Pay commission when: full payment received, or based on collected amount (configurable rule). Commission payment tracking. Pending commissions report. |

**Important:** Agent fees and commission percentages **differ by client**. The system supports **configurable** commission rules (e.g. per project, per agent, or global defaults) so each deployment can set its own rates and triggers.

### 3F. Reports & Analytics

Standard reports to include:

- Sales report (daily, monthly, yearly)
- Outstanding balances report
- Excess payments report
- Agent commission report
- Seller payable report
- Profit & loss summary
- Property availability report
- Cash flow report

### 3G. User Roles & Permissions

- Admin • Accountant • Sales Agent • Manager • Viewer  
- **Role-based access control** for security (who can see/edit what).

### 3H. Document Management

- Upload contracts, payment proof, seller agreements.
- Generate PDF receipts.
- Generate sale agreements automatically.

### 3I. Optional Advanced Features (Enterprise-level)

- SMS notification for payment reminder
- WhatsApp payment confirmation
- QR code on receipt
- Multi-branch support
- Multi-currency support
- Dashboard with KPI cards
- Audit log (who edited what)
- Land installment schedule auto-reminder

---

## 4. Core Requirements (From Client Discussion)

Below is the structured breakdown of what the customer needs and how it maps to system design.

### 4.1 Issue Statement of Excess Payment

When a buyer pays **more than required**:

- **System should:**
  - Detect overpayment  
  - Show excess balance  
  - Generate statement (PDF/print)  
- **Actions allowed:**
  - Refund  
  - Carry forward to next property  
  - Keep as credit  

### 4.2 Accrual Accounting

Instead of cash-only tracking:

- Income recognized when land is **sold** (even if not fully paid)  
- Commission recognized when sale is **confirmed**  
- Outstanding balances tracked  
- **Concepts:** Accounts Receivable, Deferred Income, Commission Payable, Revenue Recognition  

### 4.3 Agent Commission (Based on Sold Land) — *Configurable per client*

- Assign agent to each land sale  
- Commission type: **% of sale price** or **fixed amount** — **both configurable** (fees and percentages differ by client, so no hard-coded rates).  
- Calculate automatically when: sale confirmed **or** full payment completed (rule configurable per client).  
- Track unpaid commissions  
- Generate commission report per agent  
- Payment history for agents  

### 4.4 Extra Payments Added from Seller Amount

Additional charges after initial agreement, e.g.:

- Survey fee  
- Title processing  
- Transfer fee  
- Legal fee  
- Penalty  
- Documentation  

**System must:**

- Allow adding extra charges  
- Automatically update buyer balance  
- Keep audit trail (who added, when, reason)  

### 4.5 Full Property Management (Existing + Land)

- Land/plot management (project, block, plot)  
- Block & plot mapping  
- Buyer management  
- Seller management (if applicable)  
- Installment tracking  
- Receipt generation  
- Reports & statements  
- Dashboard  

### 4.6 Email System (Receive & Reply)

- **Receive email:** System must be able to receive emails (e.g. dedicated address for enquiries, support, or property-related mail). Incoming messages are stored and visible inside the application.  
- **Reply from system:** Users can read emails and send replies directly from the system (no need to switch to an external mail client).  
- **Thread & history:** Keep conversation threads and history for audit and follow-up.  
- **Optional:** Link emails to contacts, tenants, or properties where relevant.  

---

## 5. Suggested System Modules (Target Architecture)

| Module | Purpose |
|--------|--------|
| **A. Property / Land** | Project → Block → Plot (number, size, price), status: Available / Reserved / Sold / Cancelled |
| **B. Buyer management** | Buyer profile, ID, phone, address, assigned plots, payment history, statement generation |
| **C. Sales** | Sale agreement, plot, sale price, agent, commission %, payment plan, extra charges |
| **D. Payment** | Record payment, method, receipt number, auto balance update, overpayment detection, excess statement |
| **E. Accounting (accrual)** | sales, payments, receivables, commissions, optional journal_entries, expense_entries |
| **F. Agent** | Agent profile, commission setup, status (Pending / Approved / Paid), commission statement |
| **G. Reports** | Sales, agent commission, overpayment, outstanding balances, cash flow, profit per project |
| **H. Email (inbox & reply)** | Receive emails into the system; inbox view; reply from system; thread history; optional link to contacts/tenants/properties |

---

## 6. High-Level Database Design (Planned)

**Existing (current):**  
properties, property_units, tenants, contracts, invoices, invoice_items, invoice_payments, expenses, maintainers, maintenance_requests, users, roles, permissions, etc.

**To add for land selling & accrual:**

- `projects`, `blocks`, `plots`  
- `customers` (or `buyers` / tenants unified)  
- `sellers`  
- `sales`, `sale_installments`, `sale_extra_charges`  
- `payments` (land/sale payments)  
- `agents`  
- `commissions`, `commission_payments`  
- `receivables`  
- `expenses`, `income` (if not already covered)  
- `user_roles` (if extending beyond existing roles)  
- `activity_logs` (audit: who edited what)  
- `emails` / `email_threads` (inbound emails and replies for inbox & reply-from-system)  

*(Exact table names and fields to be finalised in technical design.)*

---

## 7. Business Logic (Planned)

### Overpayment

- If **Total Paid > Sale Total** → **Excess = Total Paid − Sale Total**  
- Store in e.g. `buyer_credit_balance`  
- Allow: Refund **or** carry forward  

### Commission

- **Configurable:** Commission = Sale Price × (configured %) or fixed amount. Rates and rules are **configurable per client/agent/project** (no hard-coded percentages).  
- Trigger when: **Sale status = confirmed** **or** **Payment completed** (client to confirm; can be set per deployment).  

---

## 8. Based on What You Told Me Earlier — System Focus

Because the client specifically asked for:

- **Issue statement of excess payment** ✅  
- **Accrual system** ✅  
- **Agent commission based on land sold** (with **configurable** fees and percentages) ✅  
- **Extra payments added from seller amount** ✅  

The system must **strongly focus** on:

1. **Payment reconciliation logic** — overpayment detection, credit balance, refund/carry forward.  
2. **Commission calculation engine** — configurable % or fixed amount; rules for when commission is due.  
3. **Seller payable tracking** — amount paid to seller, remaining balance, extra charges from seller side.  
4. **Clear financial reporting** — statements for customers, sellers, and agents; P&L; cash flow.  

---

## 9. Points to Clarify With Client

Before finalising build:

| Topic | Question |
|-------|----------|
| **Commission timing** | When should commission be calculated? On agreement? On full payment? On first installment? |
| **Commission rules** | Confirm that fees and percentages will be **configurable** per client/agent/project. |
| **Cancelled sale** | If a sale is cancelled: reverse commission? Refund buyer? How to handle partial payments? |
| **Excess payment** | Preferred treatment: Refund only? Credit only? Adjust future plots? Or all options? |
| **Scale** | Multi-project? Multi-branch? Multiple users with different roles? |
| **Reporting** | Any specific report formats, frequency, or regulatory requirements? |
| **Email** | Which address(es) to receive into the system? Link emails to contacts/tenants/properties? |

---

## 10. Technology Stack (Current & Suggested)

**Current:**

- Laravel backend  
- Blade views, AJAX  
- MySQL  
- Role-based access  
- PDF capability (e.g. statements)  
- Dashboard (e.g. Chart.js)  
- Audit / logging  

**Suggested additions:**

- **Email system:** Inbound email (IMAP or similar) so the system can receive emails; outbound via existing SMTP so users can reply from within the system; inbox UI and thread storage.  
- API layer (for future mobile/third-party integration)  
- WhatsApp notifications (building on existing WhatsApp experience)  
- SMS notifications  

---

## 11. Business Opportunity

This system can be productised as:

**“Real Estate Management System – Tanzania Version”**

**Target customers:**

- Land developers  
- Property companies  
- Real estate agents  

**Recurring revenue options:**

- Hosting fee  
- Maintenance and support  
- Updates and new modules  
- SMS / WhatsApp notification packages  

---

## 12. Summary Table

| Category | Available now | Planned / needed |
|----------|----------------|------------------|
| **Property (rental)** | ✅ Properties, units, types | — |
| **Tenants & contracts** | ✅ Tenants, contracts, documents | — |
| **Invoicing & payments** | ✅ Invoices, items, payments (Stripe, PayPal, bank) | Land-specific payment logic, overpayment, statements |
| **Expenses & maintenance** | ✅ Expenses, maintainers, requests | — |
| **Users & security** | ✅ Users, roles, permissions, audit | — |
| **Land selling** | — | ✅ Projects, blocks, plots, buyers, sales, installments |
| **Agents & commission** | — | ✅ Agents; **configurable** fees & % per client; commission rules, reports, payments |
| **Accounting** | Cash-oriented | ✅ Accrual, receivables, deferred income, commission payable |
| **Extra charges & audit** | — | ✅ Extra charges, audit trail |
| **Reports** | Basic | ✅ Sales, commission, overpayment, outstanding, cash flow, profit |
| **Email (inbox & reply)** | — | ✅ Receive email, view in system, reply from system, thread history |
| **Communications** | — | ✅ WhatsApp, SMS (future) |
| **Document management** | — | ✅ Contracts, payment proof, PDF receipts, auto sale agreements |
| **Roles & permissions** | ✅ Basic | ✅ Admin, Accountant, Sales Agent, Manager, Viewer; role-based access |

---

*This document is intended for customer alignment and scope discussion. Implementation details and timelines will be agreed after the clarification points in Section 9 are resolved.*
