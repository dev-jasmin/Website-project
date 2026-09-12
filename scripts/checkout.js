document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
    radio.addEventListener('change', () => {
      document.getElementById('codInstructions').hidden = radio.value !== 'cod';
      document.getElementById('gcashInstructions').hidden = radio.value !== 'gcash';
      document.getElementById('mayaInstructions').hidden = radio.value !== 'maya';
    });
  });