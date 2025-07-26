document.addEventListener('DOMContentLoaded', function () {
  // Plan toggle logic
  const toggleBtns = document.querySelectorAll('.toggle-btn');
  const planPrices = document.querySelectorAll('.plan-price');
  const perTexts = document.querySelectorAll('.plan-card__per');

  toggleBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      toggleBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const selected = btn.getAttribute('data-plan');
      planPrices.forEach(price => {
        let val = price.getAttribute('data-monthly');
        let per = '/ per month';
        if (selected === 'annually') {
          val = price.getAttribute('data-annually');
          per = '/ per year';
        }
        price.textContent = '₹' + val;
        price.nextElementSibling.textContent = per;
      });
    });
  });
});