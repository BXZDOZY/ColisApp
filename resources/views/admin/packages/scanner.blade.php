@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-dark">Scanner QR Code</h2>
        <p class="text-muted">Scannez le ticket pour valider la livraison du colis</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-white p-0">
                <nav>
                    <div class="nav nav-tabs nav-fill border-0" id="nav-tab" role="tablist">
                        <button class="nav-link active py-3 border-0 rounded-0" id="nav-camera-tab" data-bs-toggle="tab" data-bs-target="#nav-camera" type="button" role="tab"><i class="fas fa-camera me-2"></i> Caméra</button>
                        <button class="nav-link py-3 border-0 rounded-0" id="nav-upload-tab" data-bs-toggle="tab" data-bs-target="#nav-upload" type="button" role="tab"><i class="fas fa-upload me-2"></i> Télécharger</button>
                    </div>
                </nav>
            </div>
            <div class="tab-content" id="nav-tabContent">
                <!-- Camera Tab -->
                <div class="tab-pane fade show active" id="nav-camera" role="tabpanel">
                    <div id="reader" style="width: 100%;"></div>
                    <div class="card-body text-center p-4">
                        <div id="result" class="mt-3">
                            <p class="text-muted"><i class="fas fa-camera me-2"></i> En attente d'un scan...</p>
                        </div>
                        <button class="btn btn-primary mt-3 w-100 py-3 fw-bold" onclick="startScanner()" id="start-btn">
                            <i class="fas fa-play me-2"></i> Démarrer la caméra
                        </button>
                    </div>
                </div>
                <!-- Upload Tab -->
                <div class="tab-pane fade" id="nav-upload" role="tabpanel">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-file-import fa-4x text-primary-subtle"></i>
                        </div>
                        <h5 class="fw-bold">Télécharger un ticket</h5>
                        <p class="text-muted small mb-4">Sélectionnez une image ou un PDF du ticket contenant le QR Code</p>
                        <input type="file" id="qr-input-file" accept="image/*,.pdf" class="form-control mb-3">
                        <div id="upload-result" class="text-danger small"></div>
                        <canvas id="pdf-canvas" style="display:none;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
    // Configure PDF.js
    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

    const html5QrCode = new Html5Qrcode("reader");
    const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

    function handleRedirection(decodedText) {
        console.log("QR Code decoded:", decodedText);
        
        let targetUrl = decodedText;

        // If the decoded text is a full URL and contains our validation path
        if (decodedText.includes('/admin/packages/validate/')) {
            const parts = decodedText.split('/');
            const trackingNumber = parts[parts.length - 1];
            // Reconstruct the URL using the CURRENT origin to avoid jumping to ngrok/localhost unexpectedly
            targetUrl = window.location.origin + '/admin/packages/validate/' + trackingNumber;
        }

        window.location.href = targetUrl;
    }

    function onScanSuccess(decodedText, decodedResult) {
        try {
            html5QrCode.stop().then(() => {
                handleRedirection(decodedText);
            }).catch(() => {
                handleRedirection(decodedText);
            });
        } catch(e) {
            handleRedirection(decodedText);
        }
    }

    function startScanner() {
        document.getElementById('start-btn').classList.add('d-none');
        html5QrCode.start(
            { facingMode: "environment" }, 
            qrConfig,
            onScanSuccess
        ).catch(err => {
            console.error(err);
            alert("Erreur d'accès à la caméra");
            document.getElementById('start-btn').classList.remove('d-none');
        });
    }

    // Handle File Upload (Image or PDF)
    const fileinput = document.getElementById('qr-input-file');
    const uploadResult = document.getElementById('upload-result');

    fileinput.addEventListener('change', async e => {
        if (e.target.files.length == 0) return;
        
        uploadResult.innerText = "Traitement en cours...";
        uploadResult.classList.remove('text-danger');
        uploadResult.classList.add('text-primary');
        
        const file = e.target.files[0];
        console.log("File selected:", file.name, file.type);

        const html5QrCodeScanner = new Html5Qrcode("upload-result", { verbose: false });

        if (file.type === 'application/pdf') {
            try {
                const reader = new FileReader();
                reader.onload = async function() {
                    try {
                        const typedarray = new Uint8Array(this.result);
                        const pdf = await pdfjsLib.getDocument(typedarray).promise;
                        const page = await pdf.getPage(1);
                        const viewport = page.getViewport({scale: 2.0});
                        const canvas = document.getElementById('pdf-canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        await page.render({canvasContext: context, viewport: viewport}).promise;
                        
                        console.log("PDF page rendered to canvas, starting scan...");
                        
                        // Convert canvas to blob to use scanFile
                        canvas.toBlob(blob => {
                            const blobFile = new File([blob], "scan.png", { type: "image/png" });
                            html5QrCodeScanner.scanFile(blobFile, true)
                                .then(decodedText => {
                                    console.log("QR Code found in PDF:", decodedText);
                                    handleRedirection(decodedText);
                                })
                                .catch(err => {
                                    console.warn("Scan failed for PDF canvas:", err);
                                    uploadResult.innerText = "QR Code non trouvé dans le PDF.";
                                    uploadResult.classList.remove('text-primary');
                                    uploadResult.classList.add('text-danger');
                                });
                        }, 'image/png');
                    } catch (err) {
                        console.error("Error processing PDF:", err);
                        uploadResult.innerText = "Erreur lors du traitement du PDF.";
                        uploadResult.classList.remove('text-primary');
                        uploadResult.classList.add('text-danger');
                    }
                };
                reader.onerror = () => {
                    uploadResult.innerText = "Erreur de lecture du fichier.";
                    uploadResult.classList.remove('text-primary');
                    uploadResult.classList.add('text-danger');
                };
                reader.readAsArrayBuffer(file);
            } catch (err) {
                console.error("Outer PDF error:", err);
                uploadResult.innerText = "Erreur fatale lors de l'accès au PDF.";
                uploadResult.classList.remove('text-primary');
                uploadResult.classList.add('text-danger');
            }
        } else {
            // Standard image scan
            html5QrCodeScanner.scanFile(file, true)
                .then(decodedText => {
                    console.log("QR Code found in image:", decodedText);
                    handleRedirection(decodedText);
                })
                .catch(err => {
                    console.warn("Scan failed for image:", err);
                    uploadResult.innerText = "QR Code non trouvé dans cette image.";
                    uploadResult.classList.remove('text-primary');
                    uploadResult.classList.add('text-danger');
                });
        }
    });
</script>
@endsection
