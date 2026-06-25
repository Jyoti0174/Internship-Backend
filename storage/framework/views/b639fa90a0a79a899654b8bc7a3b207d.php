<!DOCTYPE html>
<html>
<head><title>Ticket Details</title></head>
<body>
<h1>Ticket #<?php echo e($ticket->id); ?></h1>
<a href="<?php echo e(route('tickets.index')); ?>">← Back</a><br><br>
<p><strong>Title:</strong> <?php echo e($ticket->title); ?></p>
<p><strong>Description:</strong> <?php echo e($ticket->description); ?></p>
<p><strong>Status:</strong> <?php echo e($ticket->status); ?></p>
<p><strong>Priority:</strong> <?php echo e($ticket->priority); ?></p>
<p><strong>Created by:</strong> <?php echo e($ticket->user->name ?? 'N/A'); ?></p>
<p><strong>Created at:</strong> <?php echo e($ticket->created_at); ?></p>
<a href="<?php echo e(route('tickets.edit', $ticket->id)); ?>">Edit</a>
</body>
</html><?php /**PATH C:\Users\Jyoti Pandey\Downloads\internship-backend\internship-backend\resources\views/tickets/show.blade.php ENDPATH**/ ?>