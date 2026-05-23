(() => {
  const demoraValidacion = 3000;
  const obtenerInputCampo = (campo) => campo.querySelector("input, select, textarea");
  const obtenerValorCampo = (campo) => {
    const tipo = campo.dataset.imCampo;

    if (tipo === "checkbox") {
      return [...campo.querySelectorAll('input[type="checkbox"]:checked')].map((input) => input.value);
    }

    if (tipo === "radio") {
      return campo.querySelector('input[type="radio"]:checked')?.value || "";
    }

    if (tipo === "toggle") {
      return campo.querySelector('input[type="checkbox"]')?.checked ? "true" : "";
    }

    if (tipo === "slider") {
      return campo.querySelector('input[type="range"]')?.value || "";
    }

    return obtenerInputCampo(campo)?.value || "";
  };
  const soloDigitos = (valor) => valor.replace(/\D/g, "");
  const normalizarImporte = (valor) => valor.trim().replace(/\$/g, "").replace(/\./g, "").replace(",", ".");
  const esperar = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
  const formatearImporte = (valor) => {
    const normalizado = normalizarImporte(valor);
    const numero = Number(normalizado);

    if (!Number.isFinite(numero)) {
      return valor;
    }

    return new Intl.NumberFormat("es-AR", {
      minimumFractionDigits: normalizado.includes(".") ? 2 : 0,
      maximumFractionDigits: 2,
    }).format(numero);
  };

  const obtenerMaximoTexto = (campo) => {
    const input = obtenerInputCampo(campo);
    return Number(campo.dataset.imMax || input?.getAttribute("maxlength") || 255);
  };
  const formatearFechaInput = (valor) => {
    const digitos = soloDigitos(valor).slice(0, 8);
    return digitos.replace(/^(\d{2})(\d)/, "$1/$2").replace(/^(\d{2})\/(\d{2})(\d)/, "$1/$2/$3");
  };
  const formatearHoraInput = (valor) => {
    const digitos = soloDigitos(valor).slice(0, 4);
    return digitos.replace(/^(\d{2})(\d)/, "$1:$2");
  };
  const parsearFecha = (valor) => {
    const partes = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(valor.trim());
    if (!partes) {
      return null;
    }

    const dia = Number(partes[1]);
    const mes = Number(partes[2]) - 1;
    const anio = Number(partes[3]);
    const fecha = new Date(anio, mes, dia);

    if (fecha.getFullYear() !== anio || fecha.getMonth() !== mes || fecha.getDate() !== dia) {
      return null;
    }

    return fecha;
  };
  const formatearFecha = (fecha) => {
    const dia = String(fecha.getDate()).padStart(2, "0");
    const mes = String(fecha.getMonth() + 1).padStart(2, "0");
    return `${dia}/${mes}/${fecha.getFullYear()}`;
  };

  const mostrarSnackbar = (mensaje) => {
    const snackbar = document.querySelector(".im-snackbar");
    if (!snackbar) {
      return;
    }

    snackbar.querySelector("span").textContent = mensaje;
    snackbar.classList.add("abierto");
    clearTimeout(window.__imSnackbarTimer);
    window.__imSnackbarTimer = setTimeout(() => snackbar.classList.remove("abierto"), 3600);
  };

  const validarCuit = (valor) => {
    const cuit = soloDigitos(valor);
    if (cuit.length !== 11) {
      return false;
    }

    const multiplicadores = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
    const suma = multiplicadores.reduce((total, multiplicador, indice) => total + Number(cuit[indice]) * multiplicador, 0);
    const resto = suma % 11;
    const digito = resto === 0 ? 0 : resto === 1 ? 9 : 11 - resto;

    return digito === Number(cuit[10]);
  };

  const validadoresCampo = {
    email: {
      validar: (valor) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor.trim()),
      mensaje: "Ingresar un email valido.",
      ok: "Email validado correctamente.",
    },
    whatsapp: {
      validar: (valor) => {
        const digitos = soloDigitos(valor);
        return digitos.length >= 10 && digitos.length <= 15;
      },
      mensaje: "Ingresar WhatsApp con codigo de area.",
      ok: "WhatsApp validado correctamente.",
    },
    cuit: {
      validar: validarCuit,
      mensaje: "Ingresar un CUIT valido.",
      ok: "CUIT validado correctamente.",
    },
    dni: {
      validar: (valor) => /^\d{7,8}$/.test(soloDigitos(valor)),
      mensaje: "Ingresar DNI de 7 u 8 digitos.",
      ok: "DNI validado correctamente.",
    },
    nombre: {
      validar: (valor) => /^[\p{L}\s'.-]{2,80}$/u.test(valor.trim()),
      mensaje: "Ingresar un nombre valido.",
      ok: "Nombre validado correctamente.",
    },
    generico: {
      validar: (valor) => Boolean(valor.trim()),
      mensaje: "Ingresar un dato para continuar.",
      ok: "Dato validado correctamente.",
    },
    importe: {
      validar: (valor) => {
        const numero = Number(normalizarImporte(valor));
        return Number.isFinite(numero) && numero > 0;
      },
      mensaje: "Ingresar un importe mayor a cero.",
      ok: "Importe validado correctamente.",
    },
    genero: {
      validar: (valor) => Boolean(valor),
      mensaje: "Seleccionar una opcion.",
      ok: "Genero validado correctamente.",
    },
    autocomplete: {
      validar: (valor) => Boolean(valor.trim()),
      mensaje: "Seleccionar o ingresar una opcion.",
      ok: "Autocomplete validado correctamente.",
    },
    checkbox: {
      validar: (valor) => Array.isArray(valor) && valor.length > 0,
      mensaje: "Seleccionar al menos una opcion.",
      ok: "Seleccion validada correctamente.",
    },
    radio: {
      validar: (valor) => Boolean(valor),
      mensaje: "Seleccionar una opcion.",
      ok: "Seleccion validada correctamente.",
    },
    toggle: {
      validar: (valor) => valor === "true",
      mensaje: "Activar esta opcion para continuar.",
      ok: "Toggle validado correctamente.",
    },
    slider: {
      validar: (valor, campo) => {
        const minimo = Number(campo.dataset.imMin || 1);
        return Number(valor) >= minimo;
      },
      mensaje: "El valor seleccionado no alcanza el minimo requerido.",
      ok: "Slider validado correctamente.",
    },
    textarea: {
      validar: (valor, campo) => valor.trim().length > 0 && valor.length <= obtenerMaximoTexto(campo),
      mensaje: "Ingresar texto dentro del limite permitido.",
      ok: "Texto validado correctamente.",
    },
    fecha: {
      validar: (valor) => Boolean(parsearFecha(valor)),
      mensaje: "Ingresar una fecha valida con formato dd/mm/aaaa.",
      ok: "Fecha validada correctamente.",
    },
    hora: {
      validar: (valor) => /^([01]\d|2[0-3]):[0-5]\d$/.test(valor.trim()),
      mensaje: "Ingresar una hora valida con formato hh:mm.",
      ok: "Hora validada correctamente.",
    },
    password: {
      validar: (valor) => /^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(valor),
      mensaje: "Minimo 8 caracteres, con letras y numeros.",
      ok: "Password validado correctamente.",
    },
    confirmarPassword: {
      validar: (valor, campo) => {
        const nombreOriginal = campo.dataset.imConfirmar || "password";
        const origen = campo.closest("form")?.querySelector(`[name="${nombreOriginal}"]`);
        return Boolean(valor) && origen?.value === valor;
      },
      mensaje: "Las contrasenas no coinciden.",
      ok: "Confirmacion validada correctamente.",
    },
    cantidad: {
      validar: (valor, campo) => {
        const input = obtenerInputCampo(campo);
        const numero = Number(valor);
        const minimo = Number(input?.min || 1);
        return Number.isInteger(numero) && numero >= minimo;
      },
      mensaje: "Ingresar una cantidad numerica valida.",
      ok: "Cantidad validada correctamente.",
    },
  };

  const limpiarEstadoCampo = (campo) => {
    campo.classList.remove("im-campo--error");
    delete campo.dataset.imValido;
    delete campo.dataset.imEstado;
  };

  const pintarResultadoCampo = (campo, resultado) => {
    const ayuda = campo.querySelector("[data-im-error], small");
    campo.classList.toggle("im-campo--error", !resultado.valido);
    campo.dataset.imValido = String(resultado.valido);
    delete campo.dataset.imEstado;

    if (ayuda) {
      ayuda.textContent = resultado.mensaje;
    }
  };

  const obtenerResultadoCampo = (campo) => {
    const tipo = campo.dataset.imCampo;
    const input = obtenerInputCampo(campo);
    const regla = validadoresCampo[tipo];

    if (!regla) {
      return { valido: true, mensaje: "Campo valido." };
    }

    const requerido = input?.hasAttribute("required") || ["checkbox", "radio", "toggle", "slider"].includes(tipo);
    const valor = obtenerValorCampo(campo);
    const vacio = Array.isArray(valor) ? valor.length === 0 : !valor.trim();

    if (requerido && vacio) {
      return { valido: false, mensaje: "Campo requerido." };
    }

    if (!vacio && !regla.validar(valor, campo)) {
      return { valido: false, mensaje: regla.mensaje };
    }

    return { valido: true, mensaje: regla.ok || "Campo validado correctamente." };
  };

  const validarCampoAsync = async (campo) => {
    const input = obtenerInputCampo(campo);
    const tipo = campo.dataset.imCampo;
    if (!input && !["checkbox", "radio", "toggle", "slider"].includes(tipo)) {
      return true;
    }

    const version = String(Date.now());
    campo.dataset.imVersionValidacion = version;
    campo.classList.remove("im-campo--error");
    delete campo.dataset.imValido;
    campo.dataset.imEstado = "validando";

    const ayuda = campo.querySelector("[data-im-error], small");
    if (ayuda) {
      ayuda.textContent = "Validando...";
    }

    await esperar(demoraValidacion);

    if (campo.dataset.imVersionValidacion !== version) {
      return false;
    }

    const resultado = obtenerResultadoCampo(campo);
    if (resultado.valido && tipo === "importe") {
      input.value = formatearImporte(input.value);
    }
    pintarResultadoCampo(campo, resultado);
    return resultado.valido;
  };

  const actualizarContador = (campo) => {
    const input = obtenerInputCampo(campo);
    const contador = campo.querySelector("[data-im-contador]");
    if (!input || !contador) {
      return;
    }

    const maximo = obtenerMaximoTexto(campo);
    contador.textContent = `${input.value.length}/${maximo}`;
    contador.classList.toggle("im-campo__contador--excedido", input.value.length > maximo);
  };

  document.querySelectorAll("[data-im-campo]").forEach((campo) => {
    const input = obtenerInputCampo(campo);
    const tipo = campo.dataset.imCampo;
    if (!input && !["checkbox", "radio", "toggle", "slider"].includes(tipo)) {
      return;
    }

    if (tipo === "textarea") {
      const maximo = obtenerMaximoTexto(campo);
      input.setAttribute("maxlength", String(maximo));
      actualizarContador(campo);
    }

    const validarSiCorresponde = () => {
      const valor = obtenerValorCampo(campo);
      const requerido = input?.hasAttribute("required") || ["checkbox", "radio", "toggle", "slider"].includes(tipo);
      const tieneValor = Array.isArray(valor) ? valor.length > 0 : valor.trim();

      if (tieneValor || requerido) {
        validarCampoAsync(campo);
      }
    };

    if (input) {
      input.addEventListener("blur", validarSiCorresponde);

      input.addEventListener("input", () => {
        if (tipo === "textarea") {
          actualizarContador(campo);
        }

        if (tipo === "importe") {
          input.value = input.value.replace(/[^\d.,]/g, "");
        }

        if (tipo === "fecha") {
          input.value = formatearFechaInput(input.value);
        }

        if (tipo === "hora") {
          input.value = formatearHoraInput(input.value);
        }

        limpiarEstadoCampo(campo);
      });
    }

    if (["checkbox", "radio", "toggle", "slider"].includes(tipo)) {
      campo.querySelectorAll("input").forEach((opcion) => {
        const evento = opcion.type === "range" ? "input" : "change";
        opcion.addEventListener(evento, () => {
          limpiarEstadoCampo(campo);
          if (opcion.type === "range") {
            const salida = campo.querySelector("output");
            if (salida) {
              salida.textContent = opcion.value;
              salida.value = opcion.value;
            }
          }
          validarSiCorresponde();
        });
      });
    }
  });

  document.querySelectorAll("[data-im-validar]").forEach((formulario) => {
    formulario.addEventListener("submit", async (evento) => {
      evento.preventDefault();
      const campos = [...formulario.querySelectorAll("[data-im-campo]")];
      const resultados = await Promise.all(campos.map((campo) => validarCampoAsync(campo)));

      if (resultados.every(Boolean)) {
        mostrarSnackbar("Campos validados correctamente.");
      }
    });

    formulario.addEventListener("reset", () => {
      setTimeout(() => {
        formulario.querySelectorAll("[data-im-campo]").forEach(limpiarEstadoCampo);
        formulario.querySelectorAll('[data-im-campo="textarea"]').forEach(actualizarContador);
      });
    });
  });

  const selectorCortina = document.createElement("div");
  selectorCortina.className = "im-selector-cortina";
  document.body.appendChild(selectorCortina);

  const datepicker = document.createElement("div");
  datepicker.className = "im-datepicker";
  document.body.appendChild(datepicker);

  const timepicker = document.createElement("div");
  timepicker.className = "im-timepicker";
  document.body.appendChild(timepicker);

  let campoFechaActivo = null;
  let mesVisible = new Date();
  let campoHoraActivo = null;
  let horaTemporal = 2;
  let minutoTemporal = 0;
  let periodoTemporal = "PM";
  let omitirAperturaHoraPorFoco = false;

  const pintarDatepicker = () => {
    const anio = mesVisible.getFullYear();
    const mes = mesVisible.getMonth();
    const primerDia = new Date(anio, mes, 1);
    const inicio = new Date(anio, mes, 1 - ((primerDia.getDay() + 6) % 7));
    const semanas = ["LU", "MA", "MI", "JU", "VI", "SA", "DO"];
    const titulo = mesVisible.toLocaleDateString("es-AR", { month: "long", year: "numeric" });
    const fechaActual = campoFechaActivo ? parsearFecha(obtenerInputCampo(campoFechaActivo)?.value || "") : null;
    const dias = [];

    for (let i = 0; i < 42; i += 1) {
      const fecha = new Date(inicio);
      fecha.setDate(inicio.getDate() + i);
      const clases = ["im-datepicker__dia"];
      if (fecha.getMonth() !== mes) {
        clases.push("im-datepicker__dia--externo");
      }
      if (fechaActual && fecha.toDateString() === fechaActual.toDateString()) {
        clases.push("activo");
      }
      dias.push(`<button class="${clases.join(" ")}" type="button" data-fecha="${fecha.toISOString()}">${fecha.getDate()}</button>`);
    }

    datepicker.innerHTML = `
      <div class="im-datepicker__cabecera">
        <button class="im-datepicker__mes" type="button">${titulo}</button>
        <div class="im-datepicker__nav">
          <button class="material-symbols-rounded" type="button" data-mes-prev aria-label="Mes anterior">chevron_left</button>
          <button class="material-symbols-rounded" type="button" data-mes-next aria-label="Mes siguiente">chevron_right</button>
        </div>
      </div>
      <div class="im-datepicker__grilla">
        ${semanas.map((dia) => `<span class="im-datepicker__dia-semana">${dia}</span>`).join("")}
        ${dias.join("")}
      </div>
      <div class="im-datepicker__acciones">
        <button type="button" data-fecha-borrar>Borrar</button>
        <button type="button" data-fecha-hoy>Hoy</button>
      </div>
    `;
  };

  const abrirDatepicker = (campo) => {
    const input = obtenerInputCampo(campo);
    campoFechaActivo = campo;
    mesVisible = parsearFecha(input.value) || new Date();
    pintarDatepicker();
    timepicker.classList.remove("abierto");
    selectorCortina.classList.add("abierto");
    datepicker.classList.add("abierto");
  };

  document.addEventListener("click", (evento) => {
    const botonFecha = evento.target.closest("[data-im-datepicker]");
    if (botonFecha) {
      abrirDatepicker(botonFecha.closest("[data-im-campo]"));
      return;
    }

    const botonHora = evento.target.closest("[data-im-timepicker]");
    if (botonHora) {
      abrirTimepicker(botonHora.closest("[data-im-campo]"));
      return;
    }
  });

  selectorCortina.addEventListener("click", () => {
    datepicker.classList.remove("abierto");
    timepicker.classList.remove("abierto");
    selectorCortina.classList.remove("abierto");
  });

  datepicker.addEventListener("click", (evento) => {
    const input = campoFechaActivo ? obtenerInputCampo(campoFechaActivo) : null;

    if (evento.target.closest("[data-mes-prev]")) {
      mesVisible.setMonth(mesVisible.getMonth() - 1);
      pintarDatepicker();
      return;
    }

    if (evento.target.closest("[data-mes-next]")) {
      mesVisible.setMonth(mesVisible.getMonth() + 1);
      pintarDatepicker();
      return;
    }

    const dia = evento.target.closest("[data-fecha]");
    if (dia && input) {
      input.value = formatearFecha(new Date(dia.dataset.fecha));
      limpiarEstadoCampo(campoFechaActivo);
      datepicker.classList.remove("abierto");
      selectorCortina.classList.remove("abierto");
      input.focus();
      return;
    }

    if (evento.target.closest("[data-fecha-hoy]") && input) {
      input.value = formatearFecha(new Date());
      limpiarEstadoCampo(campoFechaActivo);
      datepicker.classList.remove("abierto");
      selectorCortina.classList.remove("abierto");
      input.focus();
      return;
    }

    if (evento.target.closest("[data-fecha-borrar]") && input) {
      input.value = "";
      limpiarEstadoCampo(campoFechaActivo);
      datepicker.classList.remove("abierto");
      selectorCortina.classList.remove("abierto");
      input.focus();
    }
  });

  const obtenerHoraDesdeInput = (valor) => {
    const partes = /^([01]\d|2[0-3]):([0-5]\d)$/.exec(valor.trim());
    if (!partes) {
      return { hora: 2, minuto: 0, periodo: "PM" };
    }

    const hora24 = Number(partes[1]);
    const periodo = hora24 >= 12 ? "PM" : "AM";
    const hora12 = hora24 % 12 || 12;
    return { hora: hora12, minuto: Number(partes[2]), periodo };
  };

  const hora12a24 = (hora, periodo) => {
    if (periodo === "AM") {
      return hora === 12 ? 0 : hora;
    }
    return hora === 12 ? 12 : hora + 12;
  };

  const pintarTimepicker = () => {
    const horas = Array.from({ length: 12 }, (_, indice) => indice + 1);
    const posicionAguja = horaTemporal % 12 || 12;

    timepicker.innerHTML = `
      <div class="im-timepicker__visor">
        <strong>${String(horaTemporal).padStart(2, "0")}:${String(minutoTemporal).padStart(2, "0")}</strong>
        <span>${periodoTemporal}</span>
      </div>
      <div class="im-timepicker__cuerpo">
        <div class="im-timepicker__reloj">
          <span class="im-timepicker__aguja im-timepicker__aguja--${posicionAguja}"></span>
          ${horas.map((hora) => `<button class="im-timepicker__hora im-timepicker__hora--${hora}${hora === horaTemporal ? " activo" : ""}" type="button" data-hora="${hora}">${hora}</button>`).join("")}
        </div>
        <div class="im-timepicker__periodos">
          <button class="${periodoTemporal === "AM" ? "activo" : ""}" type="button" data-periodo="AM">AM</button>
          <button class="${periodoTemporal === "PM" ? "activo" : ""}" type="button" data-periodo="PM">PM</button>
        </div>
      </div>
      <label class="im-timepicker__minutos">
        <span>Minutos</span>
        <input type="number" min="0" max="59" step="1" value="${minutoTemporal}">
      </label>
      <div class="im-timepicker__acciones">
        <button type="button" data-time-cancel>CANCEL</button>
        <button type="button" data-time-ok>OK</button>
      </div>
    `;
  };

  const abrirTimepicker = (campo) => {
    const input = obtenerInputCampo(campo);
    const hora = obtenerHoraDesdeInput(input.value);
    campoHoraActivo = campo;
    horaTemporal = hora.hora;
    minutoTemporal = hora.minuto;
    periodoTemporal = hora.periodo;
    pintarTimepicker();
    datepicker.classList.remove("abierto");
    selectorCortina.classList.add("abierto");
    timepicker.classList.add("abierto");
  };

  timepicker.addEventListener("click", (evento) => {
    const hora = evento.target.closest("[data-hora]");
    if (hora) {
      horaTemporal = Number(hora.dataset.hora);
      pintarTimepicker();
      return;
    }

    const periodo = evento.target.closest("[data-periodo]");
    if (periodo) {
      periodoTemporal = periodo.dataset.periodo;
      pintarTimepicker();
      return;
    }

    if (evento.target.closest("[data-time-cancel]")) {
      timepicker.classList.remove("abierto");
      selectorCortina.classList.remove("abierto");
      return;
    }

    if (evento.target.closest("[data-time-ok]")) {
      const input = obtenerInputCampo(campoHoraActivo);
      const minutos = Number(timepicker.querySelector(".im-timepicker__minutos input")?.value || 0);
      minutoTemporal = Math.max(0, Math.min(59, minutos));
      const hora24 = hora12a24(horaTemporal, periodoTemporal);
      input.value = `${String(hora24).padStart(2, "0")}:${String(minutoTemporal).padStart(2, "0")}`;
      limpiarEstadoCampo(campoHoraActivo);
      timepicker.classList.remove("abierto");
      selectorCortina.classList.remove("abierto");
      omitirAperturaHoraPorFoco = true;
      input.focus();
      setTimeout(() => {
        omitirAperturaHoraPorFoco = false;
      });
    }
  });

  timepicker.addEventListener("input", (evento) => {
    if (evento.target.closest(".im-timepicker__minutos input")) {
      minutoTemporal = Math.max(0, Math.min(59, Number(evento.target.value || 0)));
    }
  });

  document.querySelectorAll('[data-im-campo="hora"] input').forEach((input) => {
    input.addEventListener("focus", () => {
      if (omitirAperturaHoraPorFoco) {
        return;
      }

      abrirTimepicker(input.closest("[data-im-campo]"));
    });
  });
})();
