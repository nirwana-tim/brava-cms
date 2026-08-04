import Cropper from 'cropperjs';

const MAX_EDGE = 1920;

document.addEventListener('alpine:init', () => {
    Alpine.store('imageEditor', {
        isOpen: false,
        saving: false,
        imageUrl: null,
        alt: '',
        ratio: 0,

        _file: null,
        _callback: null,

        open(file, callback) {
            this._file = file;
            this._callback = callback;
            this.alt = file.alt || '';
            this.ratio = 0;
            this.imageUrl = URL.createObjectURL(file);
            this.isOpen = true;

            Alpine.nextTick(() => this.bootstrap());
        },

        bootstrap() {
            const image = document.getElementById('media-editor-image');
            const selection = document.getElementById('media-editor-selection');

            if (!image || !selection) {
                return;
            }

            image.$ready().then(() => {
                image.$resetTransform();
                image.$center('contain');
                selection.$initSelection(true, true);
                this.applyRatio();
            });
        },

        applyRatio() {
            const selection = document.getElementById('media-editor-selection');

            if (!selection) {
                return;
            }

            selection.aspectRatio = this.ratio > 0 ? this.ratio : NaN;
        },

        setRatio(ratio) {
            this.ratio = ratio;
            this.applyRatio();
        },

        rotate(direction) {
            const image = document.getElementById('media-editor-image');

            if (image) {
                image.$rotate(direction * 90);
            }
        },

        flip(axis) {
            const image = document.getElementById('media-editor-image');

            if (!image) {
                return;
            }

            if (axis === 'h') {
                image.$scale(-1, 1);
            } else {
                image.$scale(1, -1);
            }
        },

        resetTransform() {
            const image = document.getElementById('media-editor-image');
            const selection = document.getElementById('media-editor-selection');

            if (image) {
                image.$resetTransform();
            }

            if (selection) {
                selection.$reset();
            }

            this.applyRatio();
        },

        async save() {
            const selection = document.getElementById('media-editor-selection');

            if (!selection || this.saving) {
                return;
            }

            this.saving = true;

            try {
                let canvas = await selection.$toCanvas();
                canvas = this.downscale(canvas);

                const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', 0.9));

                if (!blob) {
                    throw new Error('Failed to generate cropped image.');
                }

                const name = this.buildName(this._file);
                const file = new File([blob], name, { type: 'image/jpeg' });
                file.alt = this.alt;

                const callback = this._callback;
                this.close();

                if (callback) {
                    callback({ file, alt: this.alt, name });
                }
            } catch (error) {
                console.error(error);
                alert('Gagal memproses gambar. Silakan coba lagi.');
            } finally {
                this.saving = false;
            }
        },

        downscale(canvas) {
            const scale = Math.min(1, MAX_EDGE / Math.max(canvas.width, canvas.height));

            if (scale >= 1) {
                return canvas;
            }

            const output = document.createElement('canvas');
            output.width = Math.round(canvas.width * scale);
            output.height = Math.round(canvas.height * scale);
            output.getContext('2d').drawImage(canvas, 0, 0, output.width, output.height);

            return output;
        },

        buildName(file) {
            const base = (file?.name ?? 'image').replace(/\.[^.]+$/, '');

            return (base || 'image') + '.jpg';
        },

        close() {
            if (this.imageUrl) {
                URL.revokeObjectURL(this.imageUrl);
            }

            this.imageUrl = null;
            this._file = null;
            this._callback = null;
            this.isOpen = false;
        },
    });
});

export default Cropper;
