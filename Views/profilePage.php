<?php
include_once ('../config/DBconfig.php');
Session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML Template</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            display: flex;
            margin-top: 50px;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        form {}

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Persoonlijke Informatie</h2>
        <form action="../app/process.php" method="post" enctype="multipart/form-data">
            <div class="form-group text-center">
                <a href="#" id="imageAnchor">
                    <img id="imagePreview" src="https://via.placeholder.com/150" alt="Upload Image"
                        class="img-thumbnail">
                </a>
                <input type="file" id="imageUpload" class="form-control-file" name="profileImage"
                    style="display: none;">
            </div>
            <div class="form-group">
                <label for="aboutMe">Iets over mijzelf:</label>
                <textarea class="form-control" id="aboutMe" name="aboutMe" rows="3"
                    placeholder="Schrijf iets over jezelf"></textarea>
            </div>
            <div class="form-group">
                <label for="hobbies">Leuk feitje:</label>
                <input type="text" class="form-control" id="hobbies" name="hobbies" placeholder="Vul je hobby's in">
            </div>
            <div class="form-group">
                <label for="hobbies">Waar kom ik vandaan?:</label>
                <input type="text" class="form-control" id="whereFrom" name="whereFrom"
                    placeholder="Vul in waar je vandaan komt">
            </div>
            <div class="form-group">
                <label for="hobbies">Wat is mijn favoriete kleur?:</label>
                <input type="text" class="form-control" id="favColour" name="favColour"
                    placeholder="Vul je favoriete kleur in">
            </div>
            <div class="form-group">
                <label for="hobbies">Wat is mijn favoriete dier?</label>
                <input type="text" class="form-control" id="favAnimal" name="favAnimal"
                    placeholder="Vul je favoriete dier in">
            </div>
            <div class="form-group">
                <label for="hobbies">Wat is mijn favoriete seizoen?:</label>
                <input type="text" class="form-control" id="favSeason" name="favSeason"
                    placeholder="Vul je favoriete seizoen in">
            </div>
            <div class="form-group">
                <label for="hobbies">Wat is mijn sterrenbeeld?:</label>
                <input type="text" class="form-control" id="starsign" name="starsign"
                    placeholder="Vul je sterrenbeeld in">
            </div>
            <div class="form-group">
                <label for="hobbies">Wat zijn mijn hobby's?:</label>
                <input type="text" class="form-control" id="hobbies" name="hobbies" placeholder="Vul je hobby's in">
            </div>
            <div class="form-group">
                <label for="hobbies">Hoe vul ik mijn dag?:</label>
                <input type="text" class="form-control" id="occupation" name="occupation"
                    placeholder="Vul je dagbesteding in">
            </div>
            <div class="form-group">
                <label for="hobbies">Hoe vul ik mijn dag?:</label>
                <input type="text" class="form-control" id="occupation" name="occupation"
                    placeholder="Vul je dagbesteding in">
            </div>
            <div class="form-group">
                <label for="emoji">Kies een aantal emoji's die het beste bij jou passen:</label>
                <input type="text" class="form-control" id="emoji" name="emoji" placeholder="Kies een emoji" readonly>
                <div class="emoji-picker">
                <emoji-picker></emoji-picker>
                </div>
            </div>
            <div class="form-group">
                <label for="hobbies">Mijn green flags?:</label>
                <input type="text" class="form-control" id="greenFlag" name="greenFlag"
                    placeholder="Vul je green flags in">
            </div>
            <div class="form-group">
                <label for="hobbies">Mijn red flags?:</label>
                <input type="text" class="form-control" id="redFlag" name="redFlag" placeholder="Vul je red flags in">
            </div>
            <div class="form-group">
                <label for="dynamicFields">Extra veld:</label>
                <div id="dynamicFieldsContainer">
                    <!-- Dynamic fields will be added here -->
                </div>
                <button type="button" class="btn btn-primary" id="addField">+</button>
            </div>
            <button type="submit" class="btn btn-success">Verzenden</button>
        </form>
    </div>

    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/insert-text-at-cursor@0.3.0/index.js"></script>
    <script type="module">
        import insertTextAtCursor from 'https://cdn.jsdelivr.net/npm/insert-text-at-cursor@0.3.0/index.js';

        document.querySelector('.emoji-picker').addEventListener('emoji-click', e => { insertTextAtCursor(document.querySelector('#emoji'), e.detail.unicode)});

        document.getElementById('imagePreview').onclick = function () {
            document.getElementById('imageUpload').click(); 
        };

        document.getElementById('imageUpload').onchange = function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('imagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        };

        $(document).ready(function () {
            var fieldCount = 0;
            $('#addField').click(function () {
                var newField = `
                    <div class="dynamic-input mb-2">
                        <input type="text" class="form-control mb-1" name="extraFields[${fieldCount}][title]" placeholder="Titel">
                        <input type="text" class="form-control mb-1" name="extraFields[${fieldCount}][content]" placeholder="Inhoud">
                        <button type="button" class="btn btn-danger removeField">-</button>
                    </div>
                `;
                $('#dynamicFieldsContainer').append(newField);
                fieldCount++;
            });

            $(document).on('click', '.removeField', function () {
                $(this).closest('.dynamic-input').remove();
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>