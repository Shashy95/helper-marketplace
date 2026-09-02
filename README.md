# Helper Marketplace — MVP Scaffold

Covers the 4 MVP pieces: helper onboarding & verification, discovery/matching,
booking flow (request-based), and job lifecycle/status tracking.

## Setup

1. Create the Laravel project (needs internet access to Packagist, which this
   sandbox doesn't have — run this on your machine):
   ```
   composer create-project laravel/laravel helper-marketplace
   cd helper-marketplace
   composer require laravel/sanctum
   ```
2. Copy the folders from this scaffold into your project root, merging with
   the existing `app/`, `database/`, `routes/` folders (don't overwrite
   `routes/api.php` if you already have one — merge the route group in).
3. Add a `role` check / admin middleware for the `admin/*` routes — not
   included here, use whatever auth setup you're standardizing on.
4. Run migrations and seed categories:
   ```
   php artisan migrate
   php artisan db:seed --class=ServiceCategorySeeder
   ```
5. Storage: `php artisan storage:link` and make sure the `private` disk
   exists in `config/filesystems.php` (verification docs shouldn't be
   publicly accessible).

## What's stubbed / left as TODO

- Notifications (SMS/push) on booking transitions — marked with `// TODO`
  in `BookingService`. Wire these to your queue like your other projects.
- Authorization on `BookingController::updateStatus` — currently anyone
  authenticated can hit it; add policy checks before this goes anywhere
  near production.
- Admin middleware for the verification queue routes.
- Frontend/API consumer — this is backend only.

## Files

- `database/migrations/*` — 8 migrations: role column, service_categories,
  helper_profiles, helper_services, verification_documents,
  availability_slots, bookings, booking_status_history
- `app/Models/*` — Eloquent models with relationships and the
  `HelperProfile::active()` scope (only approved+active helpers are
  ever eligible for discovery)
- `app/Services/BookingService.php` — the state machine; all status
  transitions go through `transition()` so the audit trail and the
  transition rules (see `Booking::TRANSITIONS`) can't be bypassed
- `app/Services/HelperMatchingService.php` — Haversine-based geo search
  with rating and date-availability filters
- `app/Http/Controllers/*` — onboarding, discovery, booking, and
  admin verification endpoints
- `routes/api.php` — route wiring for all of the above

## Notifications (added)

- `app/Jobs/SendBookingNotification.php` — one queued job for every booking
  transition; figures out who to notify (helper on `requested`, client on
  everything else) and dispatches `BookingStatusChanged`
- `app/Notifications/BookingStatusChanged.php` — uses the `database` channel
  (in-app notification feed) by default. To add SMS: create an `SmsChannel`
  (e.g. wrapping Beem or Africa's Talking, both common gateways for TZ),
  add a `toSms()` method here, and add `'sms'` to the `via()` array.
- Wired into `BookingService::request()` and `BookingService::transition()` —
  both TODOs are now live dispatch calls.
- Requires Laravel's built-in notifications table:
  ```
  php artisan notifications:table
  php artisan migrate
  ```

## Authorization (added)

- `app/Policies/BookingPolicy.php` — `transitionTo` decides who can move a
  booking to which status: only the assigned helper can
  accept/decline/start/complete; either party can cancel their own booking.
  `view` restricts `GET /bookings/{id}` to the client or helper involved.
- `app/Http/Middleware/EnsureUserIsAdmin.php` — blocks non-admins from the
  `admin/*` routes (403 if `role !== 'admin'`).
- `BookingController` now calls `Auth::user()->cannot(...)` and aborts 403
  instead of the old TODO comment.

### Registration required (Laravel 11+ uses bootstrap/app.php, not Kernel.php)

In `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias(['admin' => \App\Http\Middleware\EnsureUserIsAdmin::class]);
})
```

Policies auto-discover in modern Laravel (matching `Booking` model to
`BookingPolicy` by name), so no manual registration needed — verify with
`php artisan route:list` that the admin group actually requires the
middleware, and test a non-admin gets a 403.

If you're on Laravel 10 or earlier, register the policy in
`AuthServiceProvider::$policies` and the middleware alias in
`app/Http/Kernel.php`'s `$middlewareAliases` instead.

## Web MVP (Blade + Livewire)

Livewire components for all four flows, reusing the same models/services as
the API — no duplicated business logic.

- `app/Livewire/HelperOnboarding.php` + `resources/views/livewire/helper-onboarding.blade.php`
  — profile form + service checkboxes + document upload, all on one page
- `app/Livewire/HelperSearch.php` + `helper-search.blade.php` — search form,
  results list with distance/rating, "Book" link per result
- `app/Livewire/BookingRequestForm.php` + `booking-request-form.blade.php`
  — date/time/location form, submits via `BookingService::request()`
- `app/Livewire/BookingDetail.php` + `booking-detail.blade.php` — shows
  status + history, renders action buttons conditionally based on
  `$isHelper`/`$isClient` and the same `BookingPolicy` used by the API
- `app/Livewire/MyBookings.php` + `my-bookings.blade.php` — toggle between
  "as client" / "as helper" booking lists
- `resources/views/layouts/app.blade.php` — bare Tailwind layout with nav;
  swap Tailwind CDN/Vite setup for whatever you're already using
- `routes/web.php` — all behind `auth` middleware

### Setup

```
composer require livewire/livewire
```

Livewire auto-discovers components in `app/Livewire`, so no manual
registration needed. The layout uses Tailwind classes — either run
`npm install -D tailwindcss` and wire it into `resources/css/app.css`,
or swap the classes for plain CSS if you want to skip that setup for now.



# Availability enforcement — what changed

## New
- `database/migrations/2025_01_01_000009_add_availability_slot_id_to_bookings_table.php`
  — links each booking to the exact slot it consumed
- `app/Livewire/HelperAvailability.php` + `resources/views/livewire/helper-availability.blade.php`
  — lets a helper add/remove open slots (a helper with zero slots is
  technically active but unbookable — worth a banner nudge later)
- `routes/availability-web.php` — add `/my-availability` into your auth group,
  and probably link it from the nav (layout not touched here — add it yourself)

## Changed
- `app/Services/BookingService.php` — `request()` now takes an
  `availabilitySlotId` instead of raw date/time. Locks the slot row
  (`lockForUpdate`) inside the transaction so two clients can't book the
  same slot in a race. `transition()` now releases the slot back to
  `is_booked = false` when a booking is declined or cancelled, so it
  becomes bookable again — but NOT on completion (that slot's time has
  passed, no reason to reopen it).
- `app/Models/Booking.php` — added `availabilitySlot()` relationship
- `app/Livewire/BookingRequestForm.php` + its view — client now picks
  from the helper's actual open slots via a dropdown, instead of typing
  any date/time freehand

## You'll need to update
- **`app/Http/Controllers/BookingController.php`** (the REST API version) —
  it still calls the old `BookingService::request()` signature with raw
  `requested_date`/`requested_time`. Either update its validation to accept
  `availability_slot_id` instead, or decide the API and web flows should
  diverge (not recommended — keeps behavior inconsistent between them).
- Run the new migration: `php artisan migrate`





### Not yet built

- Login/register views — this scaffold assumes Laravel's default auth
  scaffolding (Breeze) or your own; `auth` middleware just needs a
  logged-in user with a `role`.
- Admin verification queue as a Livewire page (API route already exists
  at `/admin/verifications/pending` — just needs a Blade equivalent if
  you want it in-browser instead of via API/Postman).
- Map picker for lat/lng — currently raw number inputs; swap for a
  Leaflet/Google Maps picker once you're ready.
