// ===========================
// EFECTOS Y ANIMACIONES
// ===========================

document.addEventListener("DOMContentLoaded", function () {
  // Agregar efecto ripple a los botones
  const buttons = document.querySelectorAll(".btn");
  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      let ripple = document.createElement("span");
      ripple.classList.add("ripple");
      this.appendChild(ripple);

      let x = e.clientX - e.target.offsetLeft;
      let y = e.clientY - e.target.offsetTop;

      ripple.style.left = `${x}px`;
      ripple.style.top = `${y}px`;

      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  });

  // Animación de entrada para las cards
  const cards = document.querySelectorAll(".card");
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = "1";
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    { threshold: 0.1 }
  );

  cards.forEach((card) => {
    observer.observe(card);
  });

  // Validación de formularios con feedback visual
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const inputs = form.querySelectorAll("input[required], select[required]");
      let isValid = true;

      inputs.forEach((input) => {
        if (!input.value) {
          isValid = false;
          input.classList.add("is-invalid");

          // Crear mensaje de error si no existe
          if (
            !input.nextElementSibling ||
            !input.nextElementSibling.classList.contains("invalid-feedback")
          ) {
            const errorMsg = document.createElement("div");
            errorMsg.classList.add("invalid-feedback");
            errorMsg.textContent = "Este campo es obligatorio";
            input.parentNode.insertBefore(errorMsg, input.nextSibling);
          }
        } else {
          input.classList.remove("is-invalid");
          input.classList.add("is-valid");
        }
      });

      if (!isValid) {
        e.preventDefault();

        // Mostrar alerta
        const alert = document.createElement("div");
        alert.className = "alert alert-danger mt-3";
        alert.dataset.autoDismiss = "true";
        alert.innerHTML =
          '<i class="bi bi-exclamation-triangle-fill me-2"></i>Por favor, completa todos los campos obligatorios';
        alert.style.animation = "fadeInUp 0.5s ease";

        form.insertBefore(alert, form.firstChild);

        setTimeout(() => {
          alert.remove();
        }, 3000);
      }
    });

    // Limpiar validación al escribir
    const inputs = form.querySelectorAll("input, select");
    inputs.forEach((input) => {
      input.addEventListener("input", function () {
        this.classList.remove("is-invalid");
        const errorMsg = this.nextElementSibling;
        if (errorMsg && errorMsg.classList.contains("invalid-feedback")) {
          errorMsg.remove();
        }
      });
    });
  });

  // Tooltip para botones
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Animación del logo
  const logo = document.querySelector(".logo-img");
  if (logo) {
    logo.addEventListener("click", function () {
      this.style.animation = "pulse 0.5s ease";
      setTimeout(() => {
        this.style.animation = "";
      }, 500);
    });
  }

  // Auto-ocultar alertas después de 5 segundos
  const alerts = document.querySelectorAll('.alert[data-auto-dismiss="true"]');
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.transition = "opacity 0.5s ease";
      alert.style.opacity = "0";
      setTimeout(() => {
        alert.remove();
      }, 500);
    }, 5000);
  });

  // Efecto hover en las filas de la tabla
  const tableRows = document.querySelectorAll(".table tbody tr");
  tableRows.forEach((row) => {
    row.addEventListener("mouseenter", function () {
      this.style.backgroundColor = "rgba(100, 126, 234, 0.1)";
    });

    row.addEventListener("mouseleave", function () {
      this.style.backgroundColor = "";
    });
  });

  // Confirmación antes de enviar formularios de acción
  const actionForms = document.querySelectorAll('form[action*="actualizar"]');
  actionForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      if (!confirm("¿Estás seguro de que deseas guardar los cambios?")) {
        e.preventDefault();
      }
    });
  });

  // Agregar loading spinner a botones de submit
  const submitButtons = document.querySelectorAll('button[type="submit"]');
  submitButtons.forEach((button) => {
    const form = button.closest("form");
    if (form) {
      form.addEventListener("submit", function (e) {
        // Solo agregar el spinner si el formulario es válido
        if (form.checkValidity()) {
          button.disabled = true;
          const originalText = button.innerHTML;
          button.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
        }
      });
    }
  });

  // Formato de fecha en tiempo real
  const dateInputs = document.querySelectorAll('input[type="date"]');
  dateInputs.forEach((input) => {
    input.addEventListener("change", function () {
      if (this.value) {
        const date = new Date(this.value + "T00:00:00");
        const options = {
          weekday: "long",
          year: "numeric",
          month: "long",
          day: "numeric",
        };
        const formattedDate = date.toLocaleDateString("es-ES", options);

        // Mostrar fecha formateada
        let dateInfo = this.nextElementSibling;
        if (!dateInfo || !dateInfo.classList.contains("date-info")) {
          dateInfo = document.createElement("small");
          dateInfo.className = "date-info text-muted d-block mt-1";
          this.parentNode.appendChild(dateInfo);
        }
        dateInfo.innerHTML = `<i class="bi bi-info-circle me-1"></i>${formattedDate}`;
      }
    });
  });

  console.log("🚀 Sistema de Turnos - JavaScript cargado correctamente");
});

// Función para mostrar notificaciones toast
function showToast(message, type = "success") {
  const toastContainer =
    document.getElementById("toastContainer") || createToastContainer();

  const toast = document.createElement("div");
  toast.className = `toast align-items-center text-white bg-${type} border-0`;
  toast.setAttribute("role", "alert");
  toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

  toastContainer.appendChild(toast);
  const bsToast = new bootstrap.Toast(toast);
  bsToast.show();

  toast.addEventListener("hidden.bs.toast", () => {
    toast.remove();
  });
}

function createToastContainer() {
  const container = document.createElement("div");
  container.id = "toastContainer";
  container.className = "toast-container position-fixed top-0 end-0 p-3";
  document.body.appendChild(container);
  return container;
}
