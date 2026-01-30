Welcome to Mayfair!
===================

Hello <?php echo e($name); ?>,

Your registration has been completed successfully! 🎉

Registration Details:
--------------------
Name: <?php echo e($name); ?>

Visitor Type: <?php echo e(ucfirst($visitorType)); ?>

Mobile: <?php echo e($mobile); ?>

Registration Time: <?php echo e(now()->format('d M Y, h:i A')); ?>


Thank you for registering with Mayfair Visitor Management System. We're excited to have you here!

What's Next:
-----------
✅ Your registration details have been recorded and verified
🔒 Your information is secure and will be used only for visit management
📱 You'll receive notifications for your future visits

If you have any questions or need assistance, please feel free to contact our reception.

---
Mayfair Visitor Management System
Making your visit experience seamless and secure

© <?php echo e(date('Y')); ?> Mayfair. All rights reserved.
This is an automated email, please do not reply.
<?php /**PATH E:\GitProjects\staging\mayfair_VMS\resources\views/emails/welcome-text.blade.php ENDPATH**/ ?>