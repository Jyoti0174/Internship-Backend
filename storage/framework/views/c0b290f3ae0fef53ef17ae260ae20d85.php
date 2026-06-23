<!DOCTYPE html>
<html>
<head><title>Tickets</title></head>
<body>
<h1>All Tickets</h1>
<?php if(session('success')): ?>
    <p style="color:green"><?php echo e(session('success')); ?></p>
<?php endif; ?>
<a href="<?php echo e(route('tickets.create')); ?>">Create New Ticket</a>
<br><br>
<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Actions</th>
    </tr>
    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($ticket->id); ?></td>
        <td><?php echo e($ticket->title); ?></td>
        <td><?php echo e($ticket->status); ?></td>
        <td><?php echo e($ticket->priority); ?></td>
        <td>
            <a href="<?php echo e(route('tickets.show', $ticket->id)); ?>">View</a>
            <a href="<?php echo e(route('tickets.edit', $ticket->id)); ?>">Edit</a>
            <form action="<?php echo e(route('tickets.destroy', $ticket->id)); ?>" method="POST" style="display:inline">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
</body>
</html><?php /**PATH C:\Users\Jyoti Pandey\Downloads\internship-backend\fresh-test\resources\views/tickets/index.blade.php ENDPATH**/ ?>