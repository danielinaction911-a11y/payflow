# PayFlow - Premium Investment & Digital Banking Platform

A comprehensive full-stack investment and digital banking platform built with Laravel, Livewire, and Filament. PayFlow enables users to manage investments, execute trades, handle deposits/withdrawals, and grow their wealth through multiple income streams with complete admin management capabilities.

---

## 🎯 Platform Overview

PayFlow is a complete financial ecosystem designed for:
- **Individual Investors**: Access to managed investments, trading, and wealth management tools
- **Platform Administrators**: Full control over users, transactions, investments, and platform operations

The platform combines a responsive web application for users with a powerful admin dashboard for platform management, all built on modern Laravel technology.

---

## 👥 User Features

### 📊 Dashboard & Analytics
- **Personal Dashboard**: Real-time view of account balance, profit balance, and key metrics
- **Analytics Module**: Track performance and monitor financial activity
- **Theme Switcher**: Light/Dark mode support for comfortable viewing

### 💰 Wallet & Balance Management
- **Multi-Currency Support**: Manage wallets in different currencies
- **Real-Time Balance Tracking**: Monitor available and locked balances
- **Wallet Management**: Create and manage wallets per currency with primary wallet designation

### 📥 Deposits
- **Multiple Payment Gateways**: Support for various deposit methods
- **Flexible Deposits**: Easy deposit process with instant confirmation
- **Deposit History**: Complete transaction history with status tracking
- **Deposit Control**: Admins can enable/disable deposits and set custom messages for users

### 📤 Withdrawals
- **Flexible Withdrawal Options**: Multiple withdrawal methods available
- **Withdrawal Limits**: Daily, weekly, and monthly withdrawal limits per user
- **Withdrawal Fees**: Configurable withdrawal fee structure (fixed or percentage-based)
- **Withdrawal History**: Complete record of all withdrawal transactions
- **Smart Processing**: Automatic withdrawal validation and processing

### 💼 Investments
- **Investment Plans**: Multiple tiered investment plans with different risk/reward profiles
- **ROI Types**: Support for Daily, Weekly, Monthly, Yearly, and One-Time ROI calculations
- **Flexible Terms**: Customizable duration, minimum/maximum investment amounts
- **Capital Recovery**: Option for plans that return capital at maturity
- **Profit Tracking**: Automated profit calculations and distribution
- **Investment History**: Detailed view of all active and completed investments
- **Progress Monitoring**: Real-time progress tracking with days remaining and return percentages

### 📈 Trading
- **Trading Pairs**: Access to multiple cryptocurrency/asset trading pairs
- **Order Types**: Support for different order types (Market, Limit, etc.)
- **Buy/Sell Operations**: Execute buy (long) and sell (short) orders
- **Trade History**: Complete trade execution history and performance tracking
- **Trade Validation**: Automatic expiration and status management

### 💸 Transfers
- **Peer-to-Peer Transfers**: Send funds to other users on the platform
- **Transfer Limits**: Daily, weekly, and monthly transfer limits
- **Transfer Control**: Admins can enable/disable transfers with custom messaging
- **Transfer History**: Track all internal transfers between accounts

### 👥 Referral Program
- **Affiliate System**: Unique referral codes for each user
- **Referral Commissions**: Earn commissions on referred user activities
- **Commission Tracking**: Monitor all referral-generated income
- **Incentive Structure**: Automated commission distribution system

### 🔒 Security Features
- **Two-Factor Authentication (2FA)**: Google Authenticator support with QR codes
- **Security Center**: Manage security settings and view login activities
- **Login Activity Tracking**: Monitor all login attempts and sessions
- **Security Alerts**: Notifications for suspicious activities
- **Password Management**: Secure password change functionality
- **Profile Security**: Full control over account settings and security preferences

### 💬 Support System
- **Support Tickets**: Create and track support requests
- **Ticket Replies**: Two-way communication with support team
- **Status Tracking**: Monitor ticket progress and resolution
- **FAQ Section**: Self-service help documentation

### ⚙️ User Settings
- **Profile Management**: Edit personal information (name, phone, address, etc.)
- **Avatar Upload**: Custom user profile pictures
- **Location Services**: City, state, and country management
- **Theme Preferences**: Save preferred display theme
- **Account Status**: View account approval and compliance status
- **KYC Status**: Know-Your-Customer verification tracking
- **Financial Limits**: View and understand personal transaction limits

### 📋 Notifications
- **Real-Time Alerts**: Instant notifications for important events
- **Notification History**: Review past notifications
- **Email Notifications**: Receive important updates via email

---

## 🛠️ Admin Features

### 👨‍💼 Admin Management
- **Admin User Management**: Create, edit, and manage platform administrators
- **Role-Based Access**: Different access levels and permissions
- **Admin Activity Tracking**: Monitor admin actions and changes
- **Admin Dashboard**: Overview of platform statistics

### 👥 User Management
- **User Directory**: Complete list of all platform users
- **User Profile Editing**: Modify user information and settings
- **Status Management**: Activate, deactivate, or suspend user accounts
- **KYC Management**: Review and approve user identity verification documents
- **KYC Documents**: Store and manage user submitted documentation
- **User Restrictions**: Set individual transaction limits and restrictions
- **Status Controls**: Manage deposit, withdrawal, transfer, investment, and trading status per user
- **Custom Messaging**: Send custom messages for restricted features

### 💰 Financial Management

#### Deposits
- **Deposit Approval**: Review and approve/reject user deposits
- **Deposit History**: Complete record of all platform deposits
- **Payment Gateway Setup**: Configure multiple payment gateways
- **Deposit Settings**: Enable/disable deposits globally or per user
- **Fee Configuration**: Set deposit fees and processing rules
- **Rejection Management**: Track and manage rejected deposits with reasons

#### Withdrawals
- **Withdrawal Processing**: Approve or reject withdrawal requests
- **Withdrawal History**: Monitor all withdrawal transactions
- **Withdrawal Gateway Setup**: Configure withdrawal methods
- **Fee Management**: Set withdrawal fees (fixed or percentage)
- **Withdrawal Settings**: Global withdrawal control and restrictions
- **Limit Configuration**: Set daily, weekly, monthly limits per user

#### Wallets & Transactions
- **Wallet Management**: Create and manage user wallets across currencies
- **Balance Adjustment**: Manually adjust user balances when needed
- **Transaction History**: Complete audit trail of all transactions
- **Currency Management**: Add and configure supported currencies
- **Lock Management**: Lock/unlock funds for pending transactions
- **Transaction Monitoring**: Real-time transaction tracking

#### Transfers
- **Transfer History**: Monitor all peer-to-peer transfers
- **Transfer Settings**: Enable/disable transfers with messaging
- **Limit Management**: Configure transfer limits per user
- **Transfer Tracking**: Complete audit of internal fund movements

### 📊 Investments

#### Investment Plans
- **Plan Creation**: Create customizable investment plans
- **ROI Configuration**: Set ROI percentages and calculation types (Daily/Weekly/Monthly/Yearly/One-Time)
- **Duration Settings**: Configure investment terms
- **Amount Limits**: Set minimum and maximum investment amounts
- **Features**: Define plan-specific features and benefits
- **Popular Plans**: Mark featured investment plans
- **Capital Return**: Configure plans that return capital at maturity
- **Plan Status**: Activate/deactivate investment plans

#### Investment Tracking
- **Investment History**: Monitor all user investments
- **Investment Status**: Track active, completed, and cancelled investments
- **Profit Distribution**: Manage and track profit allocations
- **Profit Logs**: Detailed record of all profit payouts
- **Manual Profit Addition**: Manually add profits for special cases
- **ROI Verification**: Ensure accurate ROI calculations

### 📈 Trading

#### Trading Pairs
- **Pair Management**: Add and manage cryptocurrency/asset trading pairs
- **Price Management**: Set and update trading pair prices
- **Trading Status**: Enable/disable specific trading pairs
- **Pair Configuration**: Configure trading parameters

#### Trade Management
- **Trade History**: Monitor all user trades
- **Trade Approval**: Review and manage trade requests
- **Order Management**: Process market and limit orders
- **Trade Status**: Track pending, completed, and cancelled trades
- **Trade Validation**: Monitor order expirations and automatic cancellations

### 🎯 Referral System
- **Referral Management**: Monitor referral relationships
- **Commission Tracking**: Track referral commissions earned
- **Commission Configuration**: Set commission rates and structures
- **Referral History**: View all referral activities
- **Payment Status**: Monitor commission payment status

### 🔐 Security & Compliance

#### KYC Management
- **Document Verification**: Review submitted KYC documents
- **User Status Approval**: Approve or request additional verification
- **Compliance Tracking**: Track KYC status for all users
- **Document Storage**: Secure storage of verification documents

#### Login Activity
- **Activity Monitoring**: Track user login attempts and times
- **Location Tracking**: View login locations and IP addresses
- **Session Management**: Monitor active user sessions
- **Suspicious Activity**: Flag and investigate suspicious login patterns

#### Security Alerts
- **Alert History**: Monitor security-related incidents
- **Alert Types**: Different alert categories (login, transaction, etc.)
- **User Safety**: Alerts for user protection and fraud prevention

### 📧 Communication

#### Support Tickets
- **Ticket Management**: View and respond to user support requests
- **Priority Levels**: Manage ticket urgency and priority
- **Ticket Status**: Track resolution progress
- **Ticket Replies**: Send responses and updates to users

#### Mail Templates
- **Template Management**: Create and customize email templates
- **Dynamic Content**: Support for variables and personalization
- **Email Notifications**: Automated email communication to users
- **Template Categories**: Organize templates by purpose (verification, alerts, etc.)

### 📋 Platform Configuration

#### Settings
- **Global Configuration**: Platform-wide settings management
- **Feature Toggles**: Enable/disable platform features
- **System Parameters**: Configure system behavior and defaults
- **Company Information**: Manage company details for communications
- **Platform Limits**: Set global transaction limits and rules

#### FAQ Management
- **Knowledge Base**: Create and organize FAQ articles
- **Category Management**: Organize FAQs by topic
- **Visibility Control**: Publish/unpublish FAQ items

#### Policies
- **Policy Management**: Create terms of service, privacy policies, etc.
- **Version Control**: Maintain policy versions
- **User Acceptance**: Track policy acceptances

### 📊 Reporting & Analytics

#### Cron Logs
- **Automation Tracking**: Monitor automated system tasks
- **Task Execution**: Log and verify task completion
- **Error Tracking**: Identify failed automated processes
- **Schedule Monitoring**: Verify scheduled tasks run on time

#### Notifications
- **Notification History**: Review all system notifications
- **Message Tracking**: Monitor user communications
- **Notification Status**: Track read/unread notifications
- **Bulk Messaging**: Send platform-wide announcements

---

## 🏗️ Technology Stack

### Backend
- **Framework**: Laravel 12 - Enterprise PHP framework
- **UI Components**: Livewire Flux - Real-time reactive components
- **Admin Panel**: Filament 3.3 - Modern admin dashboard
- **Authentication**: Laravel Fortify + Two-Factor Authentication
- **Security**: Google 2FA integration, QR code generation

### Frontend
- **Build Tool**: Vite - Lightning-fast build tool
- **Styling**: Tailwind CSS 4.0 - Utility-first CSS framework
- **JavaScript**: Vanilla JS with Axios for HTTP requests
- **Real-time Updates**: Livewire for reactive UI components

### Database
- **Support**: MySQL/PostgreSQL compatible
- **ORM**: Eloquent for data modeling
- **Migrations**: Database versioning and management

### Additional Libraries
- **Location**: Jenssegers Agent for device detection and location tracking
- **QR Codes**: SimpleSoftwareIO QR Code generation
- **Testing**: PHPUnit for unit and feature tests
- **Development**: Laravel Pail, Tinker, Laravel Sail

---

## 🔄 How It Works - System Architecture

### User Onboarding Flow

1. **Registration**: New user creates account with email/username
2. **Profile Setup**: User completes profile information (name, phone, address, location)
3. **KYC Verification**: User submits identification documents
4. **Admin Approval**: Platform admin reviews and approves KYC
5. **Account Activation**: User account becomes fully active

### Investment Workflow

1. **Browse Plans**: User views available investment plans
2. **Select Plan**: User chooses investment plan matching their criteria
3. **Deposit Funds**: User deposits money via chosen payment gateway
4. **Create Investment**: User initiates investment with deposited funds
5. **ROI Accumulation**: System automatically calculates and accumulates profit based on plan ROI type
6. **Profit Distribution**: Profits are credited to user's profit balance
7. **Maturity**: Investment reaches end date
8. **Capital Return**: Capital returned to main balance (if applicable)
9. **Withdrawal**: User can withdraw profits and/or capital

### Trading Workflow

1. **Select Pair**: User chooses cryptocurrency/asset pair
2. **Place Order**: User places buy (long) or sell (short) order
3. **Order Processing**: System processes and validates order
4. **Execution**: Order matches and executes at set price
5. **Position Tracking**: User can monitor trade status
6. **Order Expiration**: Orders expire after set time if not filled
7. **Closure**: User can close position and realize profits/losses

### Deposit Process

1. **Request Deposit**: User selects deposit amount and method
2. **Gateway Redirect**: User sent to payment gateway
3. **Payment Processing**: User completes payment with gateway
4. **Webhook Callback**: Gateway confirms payment to platform
5. **Admin Approval**: Admin reviews and approves deposit (or auto-approve based on settings)
6. **Balance Credit**: Funds added to user's wallet balance
7. **Confirmation**: User receives deposit confirmation

### Withdrawal Process

1. **Request Withdrawal**: User enters withdrawal amount and method
2. **Validation**: System checks balance and user limits
3. **Admin Review**: Admin reviews withdrawal request
4. **Approval/Rejection**: Admin approves or rejects with optional reason
5. **Processing**: System processes payment through withdrawal gateway
6. **Notification**: User notified of withdrawal status
7. **Completion**: Funds transferred to user's external account

### Referral Earnings

1. **Referral Code**: Each user gets unique referral code
2. **Invite Others**: User shares code with potential users
3. **Registration**: New user signs up using referral code
4. **Tracking**: System tracks referrer-referee relationship
5. **Commission Calculation**: Commissions earned based on referred user's activities
6. **Auto Payment**: Commissions automatically credited to referrer's account

### Security Flow

1. **Login**: User enters credentials
2. **2FA Prompt**: User prompted for 2FA code (if enabled)
3. **Verification**: System validates 2FA code from authenticator app
4. **Access Granted**: User logged in after successful verification
5. **Activity Logging**: All login attempts recorded
6. **Alert System**: Suspicious activities trigger security alerts

---

## 📦 Data Models

### Core User Models
- **User**: Primary user account with profile, status, and limits
- **Wallet**: Multi-currency wallets with available and locked balances
- **Currency**: Supported currencies on platform

### Financial Models
- **Deposit**: User deposit records with status and methods
- **Withdrawal**: User withdrawal records and processing
- **Transfer**: Internal peer-to-peer transfers between users
- **Transaction**: Complete transaction audit trail
- **Referral**: Referrer-referee relationships
- **ReferralCommission**: Commission tracking and payments

### Investment Models
- **Investment**: Individual investment records with ROI tracking
- **InvestmentPlan**: Investment plans with terms and ROI configuration
- **ProfitLog**: Profit distribution history

### Trading Models
- **Trade**: Trade orders and executions
- **TradingPair**: Available trading pairs and pricing

### Admin & Support Models
- **Admin**: Admin user accounts
- **SupportTicket**: User support tickets
- **TicketReply**: Support ticket responses
- **KYC**: KYC verification records
- **KycDocument**: User-submitted verification documents
- **LoginActivity**: Login tracking and security
- **SecurityAlert**: Security-related alerts
- **MailTemplate**: Email communication templates

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/PostgreSQL database

### Installation

```bash
# Clone repository
git clone [repository-url]
cd payflow-starter-temp

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

### Development

```bash
# Run dev server with hot reload
npm run dev

# In another terminal, run Laravel server
php artisan serve
```

### Access Points

- **User Dashboard**: `http://localhost:8000/dashboard`
- **Admin Panel**: `http://localhost:8000/admin`
- **Login**: `http://localhost:8000/login`
- **Registration**: `http://localhost:8000/register`

---

## 🔐 Security Features

- **Two-Factor Authentication**: Google Authenticator integration
- **Login Activity Monitoring**: Track all login attempts
- **Security Alerts**: Real-time security notifications
- **Transaction Limits**: Per-user spending limits
- **KYC Verification**: Identity verification before access
- **Rate Limiting**: Protection against brute force attacks
- **CSRF Protection**: Cross-site request forgery protection
- **SQL Injection Prevention**: Parameterized queries

---

## 📈 Scalability Features

- **Multi-Currency Support**: Handle multiple currencies
- **Webhook Integration**: Third-party gateway integrations
- **Automated Tasks**: Cron jobs for background processing
- **Transaction Logging**: Complete audit trail
- **Modular Architecture**: Easy to extend and maintain
- **Admin Customization**: Configurable fees, limits, and policies

---

## 📝 License

MIT
