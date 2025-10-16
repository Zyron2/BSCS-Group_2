<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if(session('success')): ?>
            <div class="mb-4 alert alert-success text-green-700 bg-green-100 border border-green-300 rounded px-4 py-2">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 min-h-screen">
            <!-- Sidebar -->
            <div class="lg:col-span-1 h-full flex flex-col">
                <!-- Date Picker -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Select Date</h3>
                    <form method="GET" action="<?php echo e(route('bookings.index')); ?>">
                        <input type="date" name="date" value="<?php echo e($date); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-4" />
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Filter by Date</button>
                    </form>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Filters</h3>
                    <form method="GET" action="<?php echo e(route('bookings.index')); ?>">
                        <input type="hidden" name="date" value="<?php echo e($date); ?>">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search bookings..." class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Room</label>
                            <select name="room_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="">All Rooms</option>
                                <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($room->id); ?>" <?php echo e($roomId == $room->id ? 'selected' : ''); ?>><?php echo e($room->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Apply Filters</button>
                    </form>
                </div>

                <!-- Notifications (static for now) -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Notifications</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <span class="text-yellow-500">&#9888;</span>
                            <div>
                                <p class="text-sm text-gray-800">Conference Room A booking moved to 10:00 AM</p>
                                <p class="text-xs text-gray-500">5 min ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-red-500">&#10060;</span>
                            <div>
                                <p class="text-sm text-gray-800">Lab Room D booking cancelled for tomorrow</p>
                                <p class="text-xs text-gray-500">1 hour ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-green-500">&#10003;</span>
                            <div>
                                <p class="text-sm text-gray-800">New booking request for Seminar Room E</p>
                                <p class="text-xs text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow mb-8">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h2 class="text-xl font-semibold">Schedule for <?php echo e($date); ?></h2>
                        <!-- Book Room Button triggers modal -->
                        <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded flex items-center"
                            data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <span class="mr-2">+</span> Book Room
                        </button>
                    </div>
                    <div class="p-6">
                        <?php if($bookings->isEmpty()): ?>
                            <div class="text-center py-12">
                                <span class="text-gray-400 text-4xl">&#128197;</span>
                                <p class="text-gray-500 mt-4">No bookings found for this date</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <h3 class="text-lg font-semibold"><?php echo e($booking->title); ?></h3>
                                                    <span class="px-2 py-1 rounded-full text-xs
                                                        <?php echo e($booking->status === 'confirmed' ? 'bg-green-100 text-green-700' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')); ?>">
                                                        <?php echo e($booking->status); ?>

                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                                                    <span>&#128337; <?php echo e($booking->start_time); ?> - <?php echo e($booking->end_time); ?></span>
                                                    <span>&#128205; <?php echo e($booking->room->name); ?></span>
                                                    <span>&#128101; <?php echo e($booking->attendees); ?> attendees</span>
                                                </div>
                                                <p class="text-sm text-gray-600">Organized by <?php echo e($booking->organizer); ?></p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <!-- Edit Button triggers modal -->
                                                <button class="text-blue-600 hover:text-blue-800 text-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editBookingModal<?php echo e($booking->id); ?>">
                                                    Edit
                                                </button>
                                                <!-- Cancel Button triggers modal -->
                                                <button class="text-red-600 hover:text-red-800 text-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#cancelBookingModal<?php echo e($booking->id); ?>">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Booking Modal (hidden until Edit is clicked) -->
                                    <div id="editBookingModal<?php echo e($booking->id); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel<?php echo e($booking->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form method="POST" action="<?php echo e(route('bookings.update', $booking->id)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="modal-content p-4">
                                                    <h3 class="text-lg font-semibold mb-4">Edit Booking</h3>
                                                    <div class="mb-2">
                                                        <label>Room</label>
                                                        <select name="room_id" class="w-full border rounded px-2 py-1" required>
                                                            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($room->id); ?>" <?php echo e($booking->room_id == $room->id ? 'selected' : ''); ?>>
                                                                    <?php echo e($room->name); ?> (Capacity: <?php echo e($room->capacity); ?>)
                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Event Title</label>
                                                        <input type="text" name="title" class="w-full border rounded px-2 py-1" value="<?php echo e($booking->title); ?>" required />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Date</label>
                                                        <input type="date" name="date" class="w-full border rounded px-2 py-1" value="<?php echo e($booking->date); ?>" required />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Start Time</label>
                                                        <input type="time" name="start_time" class="w-full border rounded px-2 py-1" value="<?php echo e($booking->start_time); ?>" required />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>End Time</label>
                                                        <input type="time" name="end_time" class="w-full border rounded px-2 py-1" value="<?php echo e($booking->end_time); ?>" required />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Organizer</label>
                                                        <input type="text" name="organizer" class="w-full border rounded px-2 py-1" value="<?php echo e($booking->organizer); ?>" required />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Number of Attendees</label>
                                                        <input type="number" name="attendees" class="w-full border rounded px-2 py-1" value="<?php echo e($booking->attendees); ?>" required />
                                                    </div>
                                                    <div class="flex justify-end mt-4">
                                                        <button type="button" class="px-4 py-2 text-gray-600" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save Changes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Cancel Booking Modal (same logic) -->
                                    <div id="cancelBookingModal<?php echo e($booking->id); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="cancelBookingModalLabel<?php echo e($booking->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form method="POST" action="<?php echo e(route('bookings.destroy', $booking->id)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <div class="modal-content p-4">
                                                    <h3 class="text-lg font-semibold mb-4">Cancel Booking</h3>
                                                    <p>Are you sure you want to cancel <strong><?php echo e($booking->title); ?></strong>?</p>
                                                    <div class="flex justify-end mt-4">
                                                        <button type="button" class="px-4 py-2 text-gray-600" data-bs-dismiss="modal">No</button>
                                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Yes, Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold">Available Rooms</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg p-6 hover:border-gray-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold"><?php echo e($room->name); ?></h3>
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-sm">
                                            <?php echo e($room->capacity); ?> seats
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Equipment:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            <?php if(is_array($room->equipment)): ?>
                                                <?php $__currentLoopData = $room->equipment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm"><?php echo e($item); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span class="text-gray-400">None</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Today's Bookings:</h4>
                                        <div class="space-y-1">
                                            <?php
                                                $roomBookings = $bookings->filter(function($b) use ($room, $date) {
                                                    return $b->room_id == $room->id && $b->date == $date;
                                                });
                                            ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $roomBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <div class="text-sm text-gray-600">
                                                    <?php echo e($booking->start_time); ?> - <?php echo e($booking->end_time); ?>: <?php echo e($booking->title); ?>

                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <p class="text-sm text-green-600">Available all day</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- Book Room Button triggers modal -->
                                    <button type="button" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700"
                                        data-bs-toggle="modal" data-bs-target="#bookingModal">
                                        Book Room
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="<?php echo e(route('bookings.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-content p-4">
                    <h3 class="text-lg font-semibold mb-4">Book a Room</h3>
                    <div class="mb-2">
                        <label>Room</label>
                        <select name="room_id" class="w-full border rounded px-2 py-1" required>
                            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($room->id); ?>"><?php echo e($room->name); ?> (Capacity: <?php echo e($room->capacity); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Event Title</label>
                        <input type="text" name="title" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>Date</label>
                        <input type="date" name="date" value="<?php echo e($date); ?>" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>Organizer</label>
                        <input type="text" name="organizer" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="mb-2">
                        <label>Number of Attendees</label>
                        <input type="number" name="attendees" class="w-full border rounded px-2 py-1" required />
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="button" class="px-4 py-2 text-gray-600" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Book Room</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap JS for modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Close modal after form submit for Edit Booking
    document.querySelectorAll('form[action*="bookings.update"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            setTimeout(() => {
                const modalId = '#editBookingModal' + this.action.split('/').pop();
                const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector(modalId));
                modal.hide();
            }, 500);
        });
    });

    // Close modal after form submit for Cancel Booking
    document.querySelectorAll('form[action*="bookings.destroy"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            setTimeout(() => {
                const modalId = '#cancelBookingModal' + this.action.split('/').pop();
                const modal = bootstrap.Modal.getOrCreateInstance(document.querySelector(modalId));
                modal.hide();
            }, 500);
        });
    });

    // Close modal on Cancel/No button click
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = bootstrap.Modal.getOrCreateInstance(this.closest('.modal'));
            modal.hide();
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP Elitebook\Group2Laravel\BSCS-Group_2\resources\views/bookings/dashboard.blade.php ENDPATH**/ ?>