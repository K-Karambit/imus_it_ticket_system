<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Cropper</title>
    <link href="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.css" rel="stylesheet">
    <style>
        #image-preview {
            max-width: 100%;
            max-height: 400px;
        }
    </style>
</head>

<body>
    <h1>Image Cropper Example</h1>
    <input type="file" id="image-input" accept="image/*">
    <br><br>
    <div>
        <img id="image-preview" style="display: none;">
    </div>
    <br>
    <button id="crop-button" style="display: none;">Crop Image</button>
    <br><br>
    <div>
        <h3>Cropped Image:</h3>
        <canvas id="cropped-canvas"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.js"></script>
    <script>
        const imageInput = document.getElementById('image-input');
        const imagePreview = document.getElementById('image-preview');
        const cropButton = document.getElementById('crop-button');
        const croppedCanvas = document.getElementById('cropped-canvas');

        let cropper;

        // Handle file input change
        imageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    cropButton.style.display = 'inline-block';

                    // Destroy the previous cropper instance if it exists
                    if (cropper) {
                        cropper.destroy();
                    }

                    // Initialize Cropper.js
                    cropper = new Cropper(imagePreview, {
                        aspectRatio: 1, // Change this as needed (e.g., 16/9 for widescreen)
                        viewMode: 2, // Ensure the image is fully contained within the crop box
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle crop button click
        cropButton.addEventListener('click', () => {
            if (cropper) {
                const croppedImageDataURL = cropper.getCroppedCanvas().toDataURL('image/png');

                // Set the cropped image on the canvas
                const ctx = croppedCanvas.getContext('2d');
                const croppedImage = new Image();
                croppedImage.onload = () => {
                    croppedCanvas.width = croppedImage.width;
                    croppedCanvas.height = croppedImage.height;
                    ctx.drawImage(croppedImage, 0, 0);
                };
                croppedImage.src = croppedImageDataURL;

                // You can also send `croppedImageDataURL` to your server or use it further
                console.log(croppedImageDataURL); // This is the base64 data URL of the cropped image
            }
        });
    </script>
</body>

</html>