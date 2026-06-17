<!DOCTYPE html>
<html>
<head><title>Create Ticket</title></head>
<body>
<h1>Create New Ticket</h1>
<a href="<?php echo e(route('tickets.index')); ?>">← Back</a><br><br>
<?php if($errors->any()): ?>
    <ul style="color:red">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
<form method="POST" action="<?php echo e(route('tickets.store')); ?>">
    <?php echo csrf_field(); ?>
    Title: <input type="text" name="title" value="<?php echo e(old('title')); ?>"><br><br>
    Description:<br>
    <textarea name="description" rows="4" cols="40"><?php echo e(old('description')); ?></textarea><br><br>
    Status:
    <select name="status">
        <option value="open">Open</option>
        <option value="in_progress">In Progress</option>
        <option value="closed">Closed</option>
    </select><br><br>
    Priority:
    <select name="priority">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select><br><br>
    User:
    <select name="user_id">
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select><br><br>
    <button type="submit">Create Ticket</button>
</form>
</body>
</html><?php /**PATH C:\Users\Jyoti Pandey\Downloads\internship-backend\internship-backend\resources\views/tickets/create.blade.php ENDPATH**/ ?>