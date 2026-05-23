(() => {
  const aplicacion = document.querySelector(".im-aplicacion");
  const botonMenu = document.querySelector("[data-alternar-menu]");
  const cortina = document.querySelector("[data-cerrar-menu]");
  const enlaces = document.querySelectorAll("[data-seccion]");
  const paneles = document.querySelectorAll("[data-panel]");
  const consultaMovil = window.matchMedia("(max-width: 760px)");

  if (!aplicacion || !botonMenu) {
    return;
  }

  const esMovil = () => consultaMovil.matches;

  const cerrarMenuMovil = () => {
    aplicacion.dataset.menuMovil = "cerrado";
    botonMenu.setAttribute("aria-expanded", "false");
  };

  const abrirMenuMovil = () => {
    aplicacion.dataset.menuMovil = "abierto";
    botonMenu.setAttribute("aria-expanded", "true");
  };

  const alternarMenu = () => {
    if (esMovil()) {
      const estaAbierto = aplicacion.dataset.menuMovil === "abierto";
      if (estaAbierto) {
        cerrarMenuMovil();
      } else {
        abrirMenuMovil();
      }
      return;
    }

    const estaColapsado = aplicacion.dataset.menuColapsado === "true";
    aplicacion.dataset.menuColapsado = String(!estaColapsado);
    botonMenu.setAttribute("aria-expanded", String(estaColapsado));
  };

  const mostrarPanel = (id) => {
    enlaces.forEach((enlace) => {
      enlace.classList.toggle("activo", enlace.dataset.seccion === id);
    });

    paneles.forEach((panel) => {
      panel.classList.toggle("activa", panel.dataset.panel === id);
    });

    if (esMovil()) {
      cerrarMenuMovil();
    }
  };

  botonMenu.addEventListener("click", alternarMenu);
  cortina?.addEventListener("click", cerrarMenuMovil);

  enlaces.forEach((enlace) => {
    enlace.addEventListener("click", (evento) => {
      const id = enlace.dataset.seccion;
      if (!id) {
        return;
      }

      evento.preventDefault();
      history.replaceState(null, "", `#${id}`);
      mostrarPanel(id);
    });
  });

  window.addEventListener("keydown", (evento) => {
    if (evento.key === "Escape" && esMovil()) {
      cerrarMenuMovil();
    }
  });

  consultaMovil.addEventListener("change", () => {
    if (esMovil()) {
      cerrarMenuMovil();
      return;
    }

    aplicacion.dataset.menuMovil = "cerrado";
    botonMenu.setAttribute("aria-expanded", String(aplicacion.dataset.menuColapsado !== "true"));
  });

  const hashInicial = window.location.hash.replace("#", "");
  const seccionInicial = hashInicial || "dashboard";
  const existeSeccion = [...paneles].some((panel) => panel.dataset.panel === seccionInicial);

  mostrarPanel(existeSeccion ? seccionInicial : "dashboard");
  if (esMovil()) {
    cerrarMenuMovil();
  }

  const crearNodo = (html) => {
    const plantilla = document.createElement("template");
    plantilla.innerHTML = html.trim();
    return plantilla.content.firstElementChild;
  };

  const modalCortina = crearNodo('<div class="im-modal-cortina" data-cerrar-dialog></div>');
  const dialog = crearNodo(`
    <section class="im-dialog" role="dialog" aria-modal="true" aria-labelledby="im-dialog-titulo">
      <header class="im-dialog__cabecera">
        <h3 id="im-dialog-titulo">Dialog con header</h3>
        <button class="im-boton-icono" type="button" data-cerrar-dialog aria-label="Cerrar dialog">×</button>
      </header>
      <div class="im-dialog__contenido">
        <p>Este dialog separa cabecera, contenido scrollable y acciones. Sirve para confirmar acciones o editar informacion sin salir de la pantalla.</p>
        <p>Si el contenido crece, solo esta zona hace scroll y las acciones quedan disponibles.</p>
      </div>
      <footer class="im-dialog__acciones">
        <button class="im-boton im-boton--texto" type="button" data-cerrar-dialog>Cancelar</button>
        <button class="im-boton im-boton--principal" type="button" data-cerrar-dialog>Guardar</button>
      </footer>
    </section>
  `);
  const bottomSheetCortina = crearNodo('<div class="im-bottom-sheet-cortina" data-cerrar-bottom-sheet></div>');
  const bottomSheet = crearNodo(`
    <section class="im-bottom-sheet" role="dialog" aria-modal="true" aria-label="Acciones rapidas">
      <div class="im-bottom-sheet__acciones">
        <button type="button">Compartir proyecto</button>
        <button type="button">Copiar enlace CDN</button>
        <button type="button">Descargar ejemplo</button>
        <button type="button" data-cerrar-bottom-sheet>Cerrar</button>
      </div>
    </section>
  `);
  const configTemaSheet = crearNodo(`
    <section class="im-bottom-sheet im-bottom-sheet--config" role="dialog" aria-modal="true" aria-labelledby="im-config-tema-titulo">
      <header class="im-bottom-sheet__cabecera">
        <div>
          <h3 id="im-config-tema-titulo">Configurar estilos del CDN</h3>
          <p>Estos parametros se guardan en este navegador y se aplican como variables CSS.</p>
        </div>
        <button class="im-boton-icono" type="button" data-cerrar-bottom-sheet aria-label="Cerrar dialog"></button>
      </header>
      <form class="im-config-tema" data-config-tema>
        <div class="im-config-tema__grupo">
          <h4>Colores</h4>
          <label class="im-campo im-campo-color">
            <span>Principal</span>
            <input type="text" data-variable-tema="--im-color-principal">
            <input type="color" data-variable-tema-color="--im-color-principal">
          </label>
          <label class="im-campo im-campo-color">
            <span>Principal suave</span>
            <input type="text" data-variable-tema="--im-color-principal-suave">
            <input type="color" data-variable-tema-color="--im-color-principal-suave">
          </label>
          <label class="im-campo im-campo-color">
            <span>Secundario</span>
            <input type="text" data-variable-tema="--im-color-secundario">
            <input type="color" data-variable-tema-color="--im-color-secundario">
          </label>
          <label class="im-campo im-campo-color">
            <span>Fondo</span>
            <input type="text" data-variable-tema="--im-color-fondo">
            <input type="color" data-variable-tema-color="--im-color-fondo">
          </label>
          <label class="im-campo im-campo-color">
            <span>Superficie</span>
            <input type="text" data-variable-tema="--im-color-superficie">
            <input type="color" data-variable-tema-color="--im-color-superficie">
          </label>
          <label class="im-campo im-campo-color">
            <span>Texto</span>
            <input type="text" data-variable-tema="--im-color-texto">
            <input type="color" data-variable-tema-color="--im-color-texto">
          </label>
        </div>
        <div class="im-config-tema__grupo">
          <h4>Accesibilidad y forma</h4>
          <label class="im-campo">
            <span>Sombras</span>
            <select data-preset-sombra>
              <option value="suave">Suaves</option>
              <option value="media">Medias</option>
              <option value="fuerte">Fuertes</option>
            </select>
          </label>
          <label class="im-campo">
            <span>Radio de tarjetas</span>
            <select data-preset-radio>
              <option value="recto">Recto</option>
              <option value="medio">Medio</option>
              <option value="redondeado">Redondeado</option>
            </select>
          </label>
          <label class="im-campo">
            <span>Apariencia de campos</span>
            <select data-form-field-apariencia>
              <option value="outline">Outline</option>
              <option value="fill">Fill</option>
            </select>
          </label>
          <label class="im-slide-toggle">
            <input type="checkbox" data-alto-contraste>
            <span></span>
            Alto contraste
          </label>
          <div class="im-alerta im-alerta--info">Alto contraste refuerza bordes, foco, texto y superficies para mejorar legibilidad.</div>
        </div>
        <div class="im-config-tema__acciones">
          <button class="im-boton im-boton--texto" type="button" data-reset-tema>Restablecer</button>
          <button class="im-boton im-boton--tonal" type="button" data-cerrar-bottom-sheet>Cerrar</button>
          <button class="im-boton im-boton--principal" type="submit">Guardar</button>
        </div>
      </form>
    </section>
  `);
  const snackbar = crearNodo('<div class="im-snackbar" role="status"><span>Operacion realizada correctamente.</span><button type="button" data-cerrar-snackbar>Cerrar</button></div>');

  document.body.append(modalCortina, dialog, bottomSheetCortina, bottomSheet, configTemaSheet, snackbar);

  const alternarOverlay = (elementos, abrir) => {
    elementos.forEach((elemento) => elemento.classList.toggle("abierto", abrir));
  };

  document.querySelectorAll("[data-abrir-dialog]").forEach((boton) => {
    boton.addEventListener("click", () => alternarOverlay([modalCortina, dialog], true));
  });

  document.querySelectorAll("[data-cerrar-dialog]").forEach((boton) => {
    boton.addEventListener("click", () => alternarOverlay([modalCortina, dialog], false));
  });

  const cerrarBottomSheets = () => {
    alternarOverlay([bottomSheetCortina, bottomSheet, configTemaSheet], false);
  };

  document.querySelectorAll("[data-abrir-bottom-sheet]").forEach((boton) => {
    boton.addEventListener("click", () => {
      configTemaSheet.classList.remove("abierto");
      alternarOverlay([bottomSheetCortina, bottomSheet], true);
    });
  });

  document.querySelectorAll("[data-abrir-config-tema]").forEach((boton) => {
    boton.addEventListener("click", () => {
      bottomSheet.classList.remove("abierto");
      alternarOverlay([bottomSheetCortina, configTemaSheet], true);
    });
  });

  document.querySelectorAll("[data-cerrar-bottom-sheet]").forEach((boton) => {
    boton.addEventListener("click", cerrarBottomSheets);
  });

  let temporizadorSnackbar;
  document.querySelectorAll("[data-abrir-snackbar]").forEach((boton) => {
    boton.addEventListener("click", () => {
      snackbar.querySelector("span").textContent = "Operacion realizada correctamente.";
      snackbar.classList.add("abierto");
      clearTimeout(temporizadorSnackbar);
      temporizadorSnackbar = setTimeout(() => snackbar.classList.remove("abierto"), 3600);
    });
  });

  document.querySelector("[data-cerrar-snackbar]")?.addEventListener("click", () => {
    snackbar.classList.remove("abierto");
  });

  const claveTema = "impulsa.material.tema";
  const estilosBase = {
    "--im-color-principal": "#6750a4",
    "--im-color-principal-suave": "#eaddff",
    "--im-color-secundario": "#006a6a",
    "--im-color-fondo": "#f8f7fb",
    "--im-color-superficie": "#ffffff",
    "--im-color-texto": "#1d1b20",
  };
  const presetsTema = {
    sombras: {
      suave: {
        "--im-sombra-1": "0 1px 2px rgba(29, 27, 32, .08), 0 1px 3px rgba(29, 27, 32, .1)",
        "--im-sombra-2": "0 8px 24px rgba(29, 27, 32, .12)",
      },
      media: {
        "--im-sombra-1": "0 2px 6px rgba(29, 27, 32, .14)",
        "--im-sombra-2": "0 14px 34px rgba(29, 27, 32, .18)",
      },
      fuerte: {
        "--im-sombra-1": "0 4px 12px rgba(29, 27, 32, .2)",
        "--im-sombra-2": "0 20px 48px rgba(29, 27, 32, .28)",
      },
    },
    radios: {
      recto: {
        "--im-radio": "2px",
        "--im-radio-chico": "2px",
      },
      medio: {
        "--im-radio": "8px",
        "--im-radio-chico": "6px",
      },
      redondeado: {
        "--im-radio": "16px",
        "--im-radio-chico": "12px",
      },
    },
  };
  const opcionesBase = {
    sombra: "suave",
    radio: "medio",
    formField: "outline",
    altoContraste: false,
  };
  let temaActual = { ...estilosBase };
  let opcionesTema = { ...opcionesBase };

  const obtenerTemaGuardado = () => {
    try {
      return JSON.parse(localStorage.getItem(claveTema)) || {};
    } catch {
      return {};
    }
  };

  const aplicarTema = (tema) => {
    Object.entries(tema).forEach(([variable, valor]) => {
      document.documentElement.style.setProperty(variable, valor);
    });
  };

  const aplicarOpcionesTema = () => {
    aplicarTema(presetsTema.sombras[opcionesTema.sombra] || presetsTema.sombras.suave);
    aplicarTema(presetsTema.radios[opcionesTema.radio] || presetsTema.radios.medio);
    document.documentElement.dataset.altoContraste = String(Boolean(opcionesTema.altoContraste));
    document.documentElement.dataset.formField = opcionesTema.formField || "outline";
  };

  const sincronizarCamposTema = () => {
    configTemaSheet.querySelectorAll("[data-variable-tema]").forEach((campo) => {
      const variable = campo.dataset.variableTema;
      campo.value = temaActual[variable] || "";
    });

    configTemaSheet.querySelectorAll("[data-variable-tema-color]").forEach((campo) => {
      const variable = campo.dataset.variableTemaColor;
      const valor = temaActual[variable] || estilosBase[variable];
      campo.value = /^#[0-9a-f]{6}$/i.test(valor) ? valor : estilosBase[variable];
    });

    const sombra = configTemaSheet.querySelector("[data-preset-sombra]");
    const radio = configTemaSheet.querySelector("[data-preset-radio]");
    const formField = configTemaSheet.querySelector("[data-form-field-apariencia]");
    const altoContraste = configTemaSheet.querySelector("[data-alto-contraste]");

    if (sombra) {
      sombra.value = opcionesTema.sombra;
    }

    if (radio) {
      radio.value = opcionesTema.radio;
    }

    if (formField) {
      formField.value = opcionesTema.formField;
    }

    if (altoContraste) {
      altoContraste.checked = Boolean(opcionesTema.altoContraste);
    }
  };

  const guardarTema = () => {
    localStorage.setItem(claveTema, JSON.stringify({ variables: temaActual, opciones: opcionesTema }));
  };

  const temaGuardado = obtenerTemaGuardado();
  temaActual = { ...temaActual, ...(temaGuardado.variables || temaGuardado) };
  opcionesTema = { ...opcionesTema, ...(temaGuardado.opciones || {}) };
  aplicarTema(temaActual);
  aplicarOpcionesTema();
  sincronizarCamposTema();

  configTemaSheet.querySelectorAll("[data-variable-tema]").forEach((campo) => {
    campo.addEventListener("input", () => {
      const variable = campo.dataset.variableTema;
      temaActual[variable] = campo.value;
      aplicarTema({ [variable]: campo.value });
      const color = configTemaSheet.querySelector(`[data-variable-tema-color="${variable}"]`);
      if (color && /^#[0-9a-f]{6}$/i.test(campo.value)) {
        color.value = campo.value;
      }
    });
  });

  configTemaSheet.querySelectorAll("[data-variable-tema-color]").forEach((campo) => {
    campo.addEventListener("input", () => {
      const variable = campo.dataset.variableTemaColor;
      temaActual[variable] = campo.value;
      aplicarTema({ [variable]: campo.value });
      const texto = configTemaSheet.querySelector(`[data-variable-tema="${variable}"]`);
      if (texto) {
        texto.value = campo.value;
      }
    });
  });

  configTemaSheet.querySelector("[data-preset-sombra]")?.addEventListener("change", (evento) => {
    opcionesTema.sombra = evento.currentTarget.value;
    aplicarOpcionesTema();
  });

  configTemaSheet.querySelector("[data-preset-radio]")?.addEventListener("change", (evento) => {
    opcionesTema.radio = evento.currentTarget.value;
    aplicarOpcionesTema();
  });

  configTemaSheet.querySelector("[data-form-field-apariencia]")?.addEventListener("change", (evento) => {
    opcionesTema.formField = evento.currentTarget.value;
    aplicarOpcionesTema();
  });

  configTemaSheet.querySelector("[data-alto-contraste]")?.addEventListener("change", (evento) => {
    opcionesTema.altoContraste = evento.currentTarget.checked;
    aplicarOpcionesTema();
  });

  configTemaSheet.querySelector("[data-config-tema]")?.addEventListener("submit", (evento) => {
    evento.preventDefault();
    guardarTema();
    snackbar.querySelector("span").textContent = "Configuracion de estilos guardada.";
    snackbar.classList.add("abierto");
    cerrarBottomSheets();
    clearTimeout(temporizadorSnackbar);
    temporizadorSnackbar = setTimeout(() => snackbar.classList.remove("abierto"), 3600);
  });

  configTemaSheet.querySelector("[data-reset-tema]")?.addEventListener("click", () => {
    temaActual = { ...estilosBase };
    opcionesTema = { ...opcionesBase };
    aplicarTema(temaActual);
    aplicarOpcionesTema();
    sincronizarCamposTema();
    localStorage.removeItem(claveTema);
  });

  document.querySelectorAll("[data-autocomplete-input]").forEach((input) => {
    const panel = input.parentElement.querySelector("[data-autocomplete-panel]");
    const opciones = [...panel.querySelectorAll("button")];

    input.addEventListener("input", () => {
      const busqueda = input.value.toLowerCase();
      opciones.forEach((opcion) => {
        opcion.hidden = !opcion.textContent.toLowerCase().includes(busqueda);
      });
      panel.classList.add("abierto");
    });

    opciones.forEach((opcion) => {
      opcion.addEventListener("click", () => {
        input.value = opcion.textContent.trim();
        panel.classList.remove("abierto");
      });
    });
  });

  document.querySelector("[data-expandir-todo]")?.addEventListener("click", () => {
    document.querySelectorAll(".im-expansion").forEach((panel) => {
      panel.open = true;
    });
  });

  document.querySelector("[data-colapsar-todo]")?.addEventListener("click", () => {
    document.querySelectorAll(".im-expansion").forEach((panel) => {
      panel.open = false;
    });
  });

  document.querySelectorAll("[data-alternar-sidenav-demo]").forEach((boton) => {
    boton.addEventListener("click", (evento) => {
      evento.currentTarget.closest("[data-sidenav-demo]")?.classList.toggle("cerrado");
    });
  });

  document.querySelectorAll("[data-slider]").forEach((slider) => {
    const salida = slider.parentElement.querySelector("output");
    slider.addEventListener("input", () => {
      salida.value = slider.value;
      salida.textContent = slider.value;
    });
  });

  document.querySelectorAll("[data-stepper]").forEach((stepper) => {
    const pasos = [...stepper.querySelectorAll(".im-stepper__pasos button")];
    const contenido = stepper.querySelector(".im-stepper__contenido");
    let indice = 0;

    const pintarPaso = () => {
      pasos.forEach((paso, posicion) => paso.classList.toggle("activo", posicion === indice));
      contenido.textContent = `Contenido lazy del paso ${indice + 1}.`;
    };

    pasos.forEach((paso, posicion) => {
      paso.addEventListener("click", () => {
        indice = posicion;
        pintarPaso();
      });
    });

    stepper.querySelector("[data-stepper-prev]")?.addEventListener("click", () => {
      indice = Math.max(0, indice - 1);
      pintarPaso();
    });

    stepper.querySelector("[data-stepper-next]")?.addEventListener("click", () => {
      indice = Math.min(pasos.length - 1, indice + 1);
      pintarPaso();
    });

    pintarPaso();
  });

  document.querySelectorAll("[data-tabs]").forEach((tabs) => {
    const botones = [...tabs.querySelectorAll("button")];
    const panelesTabs = [];
    let siguiente = tabs.nextElementSibling;

    while (siguiente?.classList.contains("im-tab-panel")) {
      panelesTabs.push(siguiente);
      siguiente = siguiente.nextElementSibling;
    }

    botones.forEach((boton, indice) => {
      boton.addEventListener("click", () => {
        botones.forEach((item, posicion) => item.classList.toggle("activo", posicion === indice));
        panelesTabs.forEach((panel, posicion) => panel.classList.toggle("activa", posicion === indice));
      });
    });
  });

  const cerrarMenusFlotantes = () => {
    document.querySelectorAll("[data-im-menu]").forEach((menu) => {
      menu.querySelector("[data-im-menu-panel]")?.classList.remove("abierto");
      menu.querySelector("[data-im-menu-trigger]")?.setAttribute("aria-expanded", "false");
    });
  };

  document.querySelectorAll("[data-im-menu]").forEach((menu) => {
    const trigger = menu.querySelector("[data-im-menu-trigger]");
    const panel = menu.querySelector("[data-im-menu-panel]");

    trigger?.addEventListener("click", (evento) => {
      evento.stopPropagation();
      const abrir = !panel?.classList.contains("abierto");
      cerrarMenusFlotantes();
      panel?.classList.toggle("abierto", abrir);
      trigger.setAttribute("aria-expanded", String(abrir));
    });

    panel?.querySelectorAll("button").forEach((opcion) => {
      opcion.addEventListener("click", cerrarMenusFlotantes);
    });
  });

  document.addEventListener("click", (evento) => {
    if (!evento.target.closest("[data-im-menu]")) {
      cerrarMenusFlotantes();
    }
  });

  document.addEventListener("keydown", (evento) => {
    if (evento.key === "Escape") {
      cerrarMenusFlotantes();
    }
  });
})();
