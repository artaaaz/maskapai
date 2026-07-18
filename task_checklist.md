# Task Progress Checklist

## Phase 1: Admin Flight Creation & Flight Classes
- [x] Analyze existing codebase
- [ ] 1.1 Modify FlightController.store() to auto-create Economy flight_class
- [ ] 1.2 Update admin flights create form to label price as "Harga Economy (Starting Price)"
- [ ] 1.3 Update admin flights index to show flight classes
- [ ] 1.4 Restrict FlightClassController to prevent duplicate Economy
- [ ] 1.5 Update Flight model to use flight_classes.price for transactions

## Phase 2: Search & Results (Guest & Customer)
- [ ] 2.1 Fix FlightResultsController to use flight_classes for pricing
- [ ] 2.2 Update search results view to show "Mulai dari" with Economy price
- [ ] 2.3 Fix round trip support in search
- [ ] 2.4 Add filter by class, airline, price range, time

## Phase 3: Flight Detail & Class Selection
- [ ] 3.1 Redesign flight-detail view with class selection cards
- [ ] 3.2 Store selected class in session
- [ ] 3.3 Enable "Lanjut Pilih Kursi" only after class selected

## Phase 4: Booking & Payment
- [ ] 4.1 Fix BookingController to use flight_classes.price
- [ ] 4.2 Fix PaymentController to use flight_classes.price
- [ ] 4.3 Integrate extra items (bagasi, meal, priority boarding)
- [ ] 4.4 Integrate insurance selection
- [ ] 4.5 Integrate promo with real validation
- [ ] 4.6 Fix Midtrans amount to match grand total

## Phase 5: Seat Selection
- [ ] 5.1 Filter seats by selected class
- [ ] 5.2 Use seat_reservations table for real-time availability
- [ ] 5.3 Show only seats matching the selected class

## Phase 6: Staff Flow
- [ ] 6.1 Implement check-in functionality
- [ ] 6.2 Implement boarding functionality
- [ ] 6.3 Implement flight checkout
- [ ] 6.4 Track passenger status (booked → checked_in → boarded → completed/no_show)

## Phase 7: Manager Dashboard
- [ ] 7.1 Real data for all metrics
- [ ] 7.2 Revenue, occupancy rate, top flights/airlines/classes
- [ ] 7.3 Check-in/boarding/completed/no_show stats

## Phase 8: E-Ticket & Integration
- [ ] 8.1 Fix e-ticket to show class, seat, gate, boarding time
- [ ] 8.2 Ensure all roles use same database
- [ ] 8.3 End-to-end flow validation

## Phase 9: Final Audit
- [ ] 9.1 Check all routes
- [ ] 9.2 Check all controllers
- [ ] 9.3 Check all Eloquent relationships
- [ ] 9.4 Remove dummy data
- [ ] 9.5 Verify all views use database