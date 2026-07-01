<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .header { background: #4f46e5; color: white; padding: 15px 20px; border-radius: 6px 6px 0 0; }
        .content { padding: 20px 0; }
        .footer { margin-top: 20px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2 style="margin:0"><?php echo e(config('app.name')); ?> Notification</h2>
    </div>
    <div class="content">

        <?php if($eventType === 'ticket_created'): ?>
            <p>A new ticket has been created.</p>
        <?php elseif($eventType === 'ticket_assigned'): ?>
            <p>A ticket has been assigned to you.</p>
        <?php elseif($eventType === 'status_changed'): ?>
            <p>A ticket status has been updated.</p>
        <?php elseif($eventType === 'comment_added'): ?>
            <p>A new comment has been added to your ticket.</p>
        <?php endif; ?>

        <table style="width:100%; border-collapse: collapse; margin-top: 15px;">
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($value): ?>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; color: #666; width: 40%;">
                        <?php echo e(ucfirst(str_replace('_', ' ', $key))); ?>

                    </td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">
                        <?php echo e($value); ?>

                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </div>
    <div class="footer">
        <p>This is an automated notification from <?php echo e(config('app.name')); ?>.</p>
    </div>
</div>
</body>
</html><?php /**PATH C:\Users\Jyoti Pandey\Downloads\internship-backend\internship-backend\resources\views/emails/ticket_notification.blade.php ENDPATH**/ ?>