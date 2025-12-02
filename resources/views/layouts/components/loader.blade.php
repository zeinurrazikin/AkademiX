<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="loader">
    <div class="relative top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div class="bg-white p-8 rounded-lg shadow-xl">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-700">Memuat...</span>
            </div>
        </div>
    </div>
</div>

<script>
function showLoader() {
    document.getElementById('loader').classList.remove('hidden');
}

function hideLoader() {
    document.getElementById('loader').classList.add('hidden');
}
</script>