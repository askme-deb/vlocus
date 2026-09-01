<?php
    /**
     * Shared editable RC-detail inputs for the vehicle create/edit forms.
     * $data is the Vehicle model on edit, null on create.
     */
    $data = $data ?? null;

    $rcFieldTypes = [
        'rc_status'              => 'text',
        'vehicle_class'         => 'text',
        'chassis_number'        => 'text',
        'engine_number'         => 'text',
        'manufacturer'          => 'text',
        'model_name'            => 'text',
        'colour'                => 'text',
        'fuel_type'             => 'text',
        'emission_norm'         => 'text',
        'owner_name'            => 'text',
        'registration_date'     => 'date',
        'rc_expiry_date'        => 'date',
        'tax_upto'              => 'date',
        'insurance_company'     => 'text',
        'insurance_upto'        => 'date',
        'financer'              => 'text',
        'owner_address'         => 'textarea',
        'cubic_capacity'        => 'text',
        'gross_weight'          => 'text',
        'seat_capacity'         => 'text',
        'sleeper_capacity'      => 'text',
        'pucc_number'           => 'text',
        'pucc_upto'             => 'date',
        'permit_type'           => 'text',
        'permit_valid_upto'     => 'date',
        'national_permit_number'=> 'text',
        'national_permit_upto'  => 'date',
        'is_commercial'         => 'boolean',
    ];
?>

<?php if (! $__env->hasRenderedOnce('4df107ac-4226-4f13-b0ea-b0d05e6f8368')): $__env->markAsRenderedOnce('4df107ac-4226-4f13-b0ea-b0d05e6f8368'); ?>
    <style>
        /* Keep the tall "Add New Vehicle" modal within the viewport with a
           pinned header/footer and a scrolling body, even on pages whose
           global CSS disables page scrolling. Inert where the modal is absent. */
        #addVehicleModal .modal-content {
            display: flex !important;
            flex-direction: column !important;
            max-height: calc(100vh - 3.5rem) !important;
        }
        #addVehicleModal .modal-header,
        #addVehicleModal .modal-footer {
            flex: 0 0 auto;
        }
        #addVehicleModal .modal-body {
            flex: 1 1 auto;
            overflow-y: auto !important;
        }
        #addVehicleModal .modal-dialog {
            margin-top: 1.75rem;
            margin-bottom: 1.75rem;
        }

        /* Compact, evenly-spaced multi-column layout for the RC block.
           Scoped to .rc-fields-grid so host pages (e.g. the delivery-schedule
           modal, whose CSS forces a stacked 200px-label form and a
           column-flex .row) don't flatten or space it out unevenly. */
        .rc-fields-grid .row.g-3 {
            display: grid !important;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            column-gap: 20px;
            row-gap: 14px;
            margin: 0 !important;
            --bs-gutter-x: 0;
            --bs-gutter-y: 0;
        }
        /* Neutralise Bootstrap's per-child gutter margin/padding, which
           otherwise stacks on top of the grid's own row-gap. */
        .rc-fields-grid .row.g-3 > [class*="col-"] {
            flex: none;
            width: auto;
            max-width: none;
            margin: 0 !important;
            padding: 0 !important;
        }
        .rc-fields-grid .row.g-3 > .col-md-12 {
            grid-column: 1 / -1;
        }
        .rc-fields-grid .mb-3 {
            display: block !important;
            width: auto !important;
            gap: 0 !important;
            align-items: stretch !important;
            margin: 0 !important;
        }
        .rc-fields-grid .mb-3 > label {
            flex: none !important;
            display: block;
            width: auto;
            margin-bottom: 4px;
            font-weight: 600;
            font-size: .8125rem;
            color: #475569;
        }
        .rc-fields-grid .mb-3 .form-control,
        .rc-fields-grid .mb-3 .form-select,
        .rc-fields-grid .mb-3 select,
        .rc-fields-grid .mb-3 textarea {
            flex: none !important;
            width: 100% !important;
        }
        .rc-fields-grid .mb-3 textarea {
            min-height: 58px;
        }
        @media (max-width: 575.98px) {
            .rc-fields-grid .row.g-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
<?php endif; ?>

<div class="row g-3">
    <?php $__currentLoopData = \App\Models\Vehicle::RC_FIELDS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $type = $rcFieldTypes[$key] ?? 'text';
            $current = old($key, $data ? ($data->{$key} ?? null) : null);
        ?>

        <?php if($type === 'textarea'): ?>
            <div class="mb-3 col-md-12">
                <label for="<?php echo e($key); ?>" class="form-label"><?php echo e($label); ?></label>
                <textarea name="<?php echo e($key); ?>" id="<?php echo e($key); ?>" class="form-control js-rc-field" rows="2"
                    placeholder="Enter <?php echo e($label); ?>"><?php echo e($current); ?></textarea>
            </div>
        <?php elseif($type === 'boolean'): ?>
            <?php
                $boolCurrent = is_null($current) || $current === '' ? '' : ((int) filter_var($current, FILTER_VALIDATE_BOOLEAN) ? '1' : '0');
            ?>
            <div class="mb-3 col-md-4">
                <label for="<?php echo e($key); ?>" class="form-label"><?php echo e($label); ?></label>
                <select name="<?php echo e($key); ?>" id="<?php echo e($key); ?>" class="form-select js-rc-field">
                    <option value="">Not Set</option>
                    <option value="1" <?php echo e($boolCurrent === '1' ? 'selected' : ''); ?>>Yes</option>
                    <option value="0" <?php echo e($boolCurrent === '0' ? 'selected' : ''); ?>>No</option>
                </select>
            </div>
        <?php else: ?>
            <div class="mb-3 col-md-4">
                <label for="<?php echo e($key); ?>" class="form-label"><?php echo e($label); ?></label>
                <input type="text" class="form-control js-rc-field" name="<?php echo e($key); ?>" id="<?php echo e($key); ?>"
                    value="<?php echo e($current); ?>" placeholder="<?php echo e($type === 'date' ? 'DD-MM-YYYY' : 'Enter ' . $label); ?>">
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH D:\projects\vlocus\resources\views/admin/vehicle/_rc_fields.blade.php ENDPATH**/ ?>