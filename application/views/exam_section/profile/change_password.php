<script>
    //<![CDATA[
    $(document).ready(function() {
        $("#manage").validate({
            rules: {
                old_password: {
                    remote: '<?php echo EXAM_SECTION_URL . 'profile/checkOldPassword'; ?>'
                },
                confirm_password: {
                    equalTo: "#password"
                }
            },
            messages: {
                old_password: {
                    remote: 'Password did not match. Please enter again'
                },
                confirm_password: {
                    equalTo: 'Please enter the SAME NEW PASSWORD again'
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr('type') === 'radio' || element.attr('type') === 'checkbox') {
                    $('.error_generate').html(error);
                } else {
                    return false;
                }
            }
        });
    });
</script>
<div class="col-md-12">
    <h3>Edit Password</h3>
    <hr>

    <div class="col-lg-12 margin-killer padding-killer">
        <?php if ($this->session->flashdata('error') != '' || $this->session->flashdata('success') != '') { ?>
            <?php
            if ($this->session->flashdata('error') != '') {
                echo '<div class="alert alert-danger"><a href="' . current_url() . '" class="close" data-dismiss="alert">&times;</a>' . $this->session->flashdata('error') . '</div>';
            }
            ?>

            <?php
            if ($this->session->flashdata('success') != '') {
                echo '<div class="alert alert-success"><a href="' . current_url() . '" class="close" data-dismiss="alert">&times;</a>' . $this->session->flashdata('success') . '</div>';
            }
            ?>
        <?php } ?>
    </div>

    <form action="<?php echo HOSTEL_URL . 'profile/updatePassword' ?>" method="post" id="manage" class="form-horizontal">
        <div class="form-group">
            <label for="question" class="col-md-2 control-label">
                Old Password
                <span class="text-danger">*</span>
            </label>
            <div class="col-md-4">
                <input type="password" name="old_password" class="form-control required" placeholder="Old Password" autocomplete="off"/>
            </div>
        </div>

        <div class="form-group">
            <label for="question" class="col-md-2 control-label">
                New Password
                <span class="text-danger">*</span>
            </label>
            <div class="col-md-4">
                <input type="password" name="password" id="password" class="form-control required" placeholder="New Password" autocomplete="off"/>
            </div>
        </div>

        <div class="form-group">
            <label for="question" class="col-md-2 control-label">
                Confirm Password
                <span class="text-danger">*</span>
            </label>
            <div class="col-md-4">
                <input type="password" name="confirm_password" class="form-control required" placeholder="Confirm Password" autocomplete="off"/>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-2 control-label">&nbsp;</label>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?php echo EXAM_SECTION_URL; ?>" class="btn btn-inverse">Cancel</a>
            </div>
        </div>

        <div class="form-group">
            Fields marked with  <span class="text-danger">*</span>  are mandatory.
        </div>
    </form>
</div>