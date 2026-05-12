// Xác nhận trước khi xóa
document.addEventListener('click', e => {
    if (e.target.matches('.btn-confirm-delete')) {
        if (!confirm('Bạn có chắc muốn xóa không?')) e.preventDefault();
    }
});

// Tự ẩn alert sau 4 giây
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => el.style.display = 'none', 4000);
});