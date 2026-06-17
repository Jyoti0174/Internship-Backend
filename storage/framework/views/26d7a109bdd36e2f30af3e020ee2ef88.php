<!DOCTYPE html>
<html>
<head><title>Edit Ticket</title></head>
<body>
<h1>Edit Ticket #<?php echo e($ticket->id); ?></h1>
<a href="<?php echo e(route('tickets.index')); ?>">← Back</a><br><br>
<?php if($errors->any()): ?>
    <ul style="color:red">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
<form method="POST" action="<?php echo e(route('tickets.update', $ticket->id)); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    Title: <input type="text" name="title" value="<?php echo e($ticket->title); ?>"><br><br>
    Description:<br>
    <textarea name="description" rows="4" cols="40"><?php echo e($ticket->description); ?></textarea><br><br>
    Status:
    <select name="status">
        <option value="open" <?php echo e($ticket->status == 'open' ? 'selected' : ''); ?>>Open</option>
        <option value="in_progress" <?php echo e($ticket->status == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
        <option value="closed" <?php echo e($ticket->status == 'closed' ? 'selected' : ''); ?>>Closed</option>
    </select><br><br>
    Priority:
    <select name="priority">
        <option value="low" <?php echo e($ticket->priority == 'low' ? 'selected' : ''); ?>>Low</option>
        <option value="medium" <?php echo e($ticket->priority == 'medium' ? 'selected' : ''); ?>>Medium</option>
        <option value="high" <?php echo e($ticket->priority == 'high' ? 'selected' : ''); ?>>High</option>
    </select><br><br>
    User:
    <select name="user_id">
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($user->id); ?>" <?php echo e($ticket->user_id == $user->id ? 'selected' : ''); ?>>
                <?php echo e($user->name); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select><br><br>
    <button type="submit">Update Ticket</button>
</form>
</body>
</html><?php /**PATH C:\Users\Jyoti Pandey\Downloads\internship-backend\internship-backend\resources\views/tickets/edit.blade.php ENDPATH**/ ?>