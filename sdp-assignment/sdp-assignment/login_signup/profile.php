<?php
require_once '../Includes/dbConnection.php';
require_once '../Includes/generalFunc.php';

if (!is_login()) {
    header("Location: /sdp-assignment/Login_SignUp/login.php");
    exit();
}

$sql = "SELECT * FROM user WHERE userid = " . $_SESSION['id'];
$result = execSql($sql);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/sdp-assignment/Login_SignUp/profile.css">
</head>

<body>
    <div class="container-fluid light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-2" style="font-size: 2.5em">
            Profile settings
        </h4>
        <button class="backbtn" onclick="location.href ='/sdp-assignment/index.php'">Back</button>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-2 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-info">Info</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-social-links">Social links</a>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body media align-items-center">
                                <!-- Profile Image -->
                                <img src="<?php echo $user['image'] ?>" alt="" class="d-block ui-w-80">
                                <div class="media-body ml-4">
                                    <div class="form-group">
                                        <label class="form-label">Image Link</label>
                                        <input name="image_link" type="text" class="form-control mb-1" value="<?php echo $user['image'] ?>" placeholder="Enter Your Image Link">
                                    </div>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control mb-1" value="<?php echo $user['username'] ?>" placeholder="Enter Your Username">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo $user['name'] ?>" placeholder=" Enter Your Name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="text" name="email" class="form-control mb-1" value="<?php echo $user['email'] ?>" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Current password</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Enter Your Current Password">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter Your New Password">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Repeat new password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Repeat Your New Password">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Biography</label>
                                    <textarea class="form-control" name="biography" text="" rows="5" placeholder="Enter Your Bio"><?php echo $user["biography"] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Birthday</label>
                                    <input type="date" name="birthday" value="<?php echo $user["birthday"] ?>" class="form-control" placeholder="Enter Your Birthday">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">State</label>
                                    <select name="state" class="custom-select">
                                        <option hidden selected=""><?php echo $user["state"] ?></option>
                                        <option>Johor</option>
                                        <option>Kedah</option>
                                        <option>Kelantan</option>
                                        <option>Kuala Lumpur</option>
                                        <option>Melaka</option>
                                        <option>Negeri Sembilan</option>
                                        <option>Pahang</option>
                                        <option>Penang</option>
                                        <option>Perak</option>
                                        <option>Putrajaya</option>
                                        <option>Sabah</option>
                                        <option>Sarawak</option>
                                        <option>Selangor</option>
                                        <option>Terengganu</option>
                                    </select>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Contacts</h6>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input name="phone" value="<?php echo $user["phone"] ?>" type="text" class="form-control" placeholder="+60">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-social-links">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Twitter</label>
                                    <input name="twitter" value="<?php echo $user["twitter"] ?>" type="text" class="form-control" placeholder="Enter Your Twitter Link">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Facebook</label>
                                    <input name="facebook" value="<?php echo $user["facebook"] ?>" type="text" class="form-control" placeholder="Enter Your Facebook Link">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">LinkedIn</label>
                                    <input name="linkedin" value="<?php echo $user["linkedIn"] ?>" type="text" class="form-control" placeholder="Enter Your Linkedln Link">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Instagram</label>
                                    <input name="instagram" value="<?php echo $user["instagram"] ?>" type="text" class="form-control" placeholder="Enter Your Instagram Link">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right mt-3" style="margin-right: 20px;">
        <button onclick="formSubmit()" type="button" class="btn btn-primary">Save changes</button>&nbsp;
        <button type="button" class="btn btn-default">Cancel</button>
    </div>

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function formSubmit() {
            const email_re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            let image_link = document.querySelector('input[name="image_link"]').value;
            let username = document.querySelector('input[name="username"]').value;
            let name = document.querySelector('input[name="name"]').value;
            let email = document.querySelector('input[name="email"]').value;
            let biography = document.querySelector('textarea[name="biography"]').value;
            let birthday = document.querySelector('input[name="birthday"]').value;
            let state = document.querySelector('select[name="state"]').value;
            let phone = document.querySelector('input[name="phone"]').value;
            let twitter = document.querySelector('input[name="twitter"]').value;
            let facebook = document.querySelector('input[name="facebook"]').value;
            let linkedin = document.querySelector('input[name="linkedin"]').value;
            let instagram = document.querySelector('input[name="instagram"]').value;
            let current_password = document.querySelector('input[name="current_password"]').value;
            let new_password = document.querySelector('input[name="new_password"]').value;
            let confirm_password = document.querySelector('input[name="confirm_password"]').value;
            let id = <?php echo get_user_id() ?>;

            // verification
            if (new_password !== confirm_password) {
                alert("New password and confirm password are not the same");
                return;
            }
            if (email_re.test(email) === false) {
                alert("Invalid email format");
                return;
            }
            // phone always starts with 0 and have 10-11 digits
            if (phone.length < 10 || phone.length > 11 || phone[0] !== '0') {
                alert("Invalid phone number");
                return;
            }

            $.ajax({
                type: "POST",
                url: "/sdp-assignment/Login_SignUp/updateProfile.php",
                data: {
                    id: id,
                    image_link: image_link,
                    username: username,
                    name: name,
                    email: email,
                    biography: biography,
                    birthday: birthday,
                    state: state,
                    phone: phone,
                    twitter: twitter,
                    facebook: facebook,
                    linkedin: linkedin,
                    instagram: instagram,
                    current_password: current_password,
                    new_password: new_password,
                    confirm_password: confirm_password
                },
                success: function(response) {
                    if (response === "success") {
                        alert("Profile updated successfully");

                        window.location.reload();

                    } else {
                        alert(response);
                    }
                }
            });
        }
    </script>
</body>

</html>