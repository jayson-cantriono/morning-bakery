document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.qty-input').forEach(input => input.addEventListener('change', () => input.form.submit()));
});
