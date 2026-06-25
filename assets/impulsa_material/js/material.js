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
  const panelActivoInicial = document.querySelector("[data-panel].activa")?.dataset.panel;
  const primerPanel = paneles[0]?.dataset.panel;
  const seccionInicial = hashInicial || panelActivoInicial || primerPanel || "dashboard";
  const existeSeccion = [...paneles].some((panel) => panel.dataset.panel === seccionInicial);
  const seccionFallback = panelActivoInicial || primerPanel || "dashboard";

  mostrarPanel(existeSeccion ? seccionInicial : seccionFallback);
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
  const drawerCortina = crearNodo('<div class="im-drawer-cortina" data-cerrar-drawer></div>');
  const drawer = crearNodo(`
    <aside class="im-drawer" role="dialog" aria-modal="true" aria-labelledby="im-drawer-titulo">
      <header class="im-drawer__cabecera">
        <h3 id="im-drawer-titulo">Detalle del proyecto</h3>
        <button class="im-boton-icono material-symbols-rounded" type="button" data-cerrar-drawer aria-label="Cerrar drawer">close</button>
      </header>
      <div class="im-drawer__contenido">
        <p>El Drawer muestra informacion complementaria sin abandonar la vista actual.</p>
        <form class="im-formulario">
          <label class="im-campo"><span>Nombre</span><input type="text" value="Impulsa Material"></label>
          <label class="im-campo"><span>Estado</span><select><option>Activo</option><option>En revision</option></select></label>
        </form>
      </div>
      <footer class="im-drawer__acciones">
        <button class="im-boton im-boton--texto" type="button" data-cerrar-drawer>Cancelar</button>
        <button class="im-boton im-boton--principal" type="button" data-cerrar-drawer>Guardar</button>
      </footer>
    </aside>
  `);
  const snackbar = crearNodo('<div class="im-snackbar" role="status"><span>Operacion realizada correctamente.</span><button type="button" data-cerrar-snackbar>Cerrar</button></div>');

  document.body.append(modalCortina, dialog, bottomSheetCortina, bottomSheet, configTemaSheet, drawerCortina, drawer, snackbar);

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

  const cerrarDrawer = () => {
    alternarOverlay([drawerCortina, drawer], false);
  };

  document.addEventListener("click", (evento) => {
    if (evento.target.closest("[data-abrir-drawer]")) {
      alternarOverlay([drawerCortina, drawer], true);
      return;
    }

    if (evento.target.closest("[data-cerrar-drawer]")) {
      cerrarDrawer();
    }
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

  const iconosTreePorTipo = {
    archive: "folder_zip",
    audio: "audio_file",
    code: "code",
    database: "storage",
    design: "draw",
    document: "description",
    font: "format_size",
    image: "image",
    pdf: "picture_as_pdf",
    presentation: "slideshow",
    spreadsheet: "table_chart",
    stylesheet: "palette",
    video: "movie"
  };

  const extensionesTreePorTipo = {
    archive: ["7z", "bz2", "gz", "rar", "tar", "tgz", "xz", "zip"],
    audio: ["aac", "flac", "m4a", "mp3", "ogg", "wav"],
    code: ["c", "cpp", "cs", "go", "html", "java", "js", "json", "jsx", "kt", "lua", "mjs", "php", "py", "rb", "rs", "sh", "sql", "ts", "tsx", "vue", "xml", "yaml", "yml"],
    database: ["db", "sqlite", "sqlite3", "mdb"],
    design: ["ai", "fig", "psd", "sketch", "xd"],
    document: ["doc", "docx", "md", "odt", "pages", "rtf", "txt"],
    font: ["eot", "otf", "ttf", "woff", "woff2"],
    image: ["avif", "gif", "jpeg", "jpg", "png", "svg", "webp"],
    pdf: ["pdf"],
    presentation: ["key", "odp", "ppt", "pptx"],
    spreadsheet: ["csv", "ods", "tsv", "xls", "xlsx"],
    stylesheet: ["css", "less", "sass", "scss"],
    video: ["avi", "mkv", "mov", "mp4", "webm"]
  };

  const tipoTreePorExtension = Object.entries(extensionesTreePorTipo).reduce((mapa, [tipo, extensiones]) => {
    extensiones.forEach((extension) => {
      mapa[extension] = tipo;
    });
    return mapa;
  }, {});

  const obtenerTextoTree = (li, grupoHijo) => {
    if (li.dataset.treeLabel) {
      return li.dataset.treeLabel.trim();
    }

    const clon = li.cloneNode(true);
    clon.querySelectorAll("ul, ol").forEach((grupo) => grupo.remove());
    return clon.textContent.replace(/\s+/g, " ").trim();
  };

  const obtenerExtensionTree = (etiqueta) => {
    const base = etiqueta.split(/[\\/]/).pop() || etiqueta;
    const coincide = base.match(/\.([a-z0-9]+)$/i);
    return coincide ? coincide[1].toLowerCase() : "";
  };

  const resolverIconoTree = (li, esCarpeta, etiqueta) => {
    if (esCarpeta) {
      return "folder";
    }

    const tipoExplicito = (li.dataset.fileType || "").trim().toLowerCase();
    if (tipoExplicito && iconosTreePorTipo[tipoExplicito]) {
      return iconosTreePorTipo[tipoExplicito];
    }

    const extension = obtenerExtensionTree(etiqueta);
    const tipoDetectado = tipoTreePorExtension[extension];
    return tipoDetectado ? iconosTreePorTipo[tipoDetectado] : "draft";
  };

  const checkboxTreeDeNodo = (nodo) => nodo?.querySelector(":scope > .im-treeview__item .im-treeview__checkbox");
  const grupoTreeDeNodo = (nodo) => nodo?.querySelector(":scope > .im-treeview__group");
  const checkboxesTreeDeGrupo = (grupo) => grupo ? [...grupo.querySelectorAll(".im-treeview__checkbox")] : [];

  const actualizarEstadoVisualTree = (nodo) => {
    const checkbox = checkboxTreeDeNodo(nodo);
    nodo?.classList.toggle("is-selected", Boolean(checkbox?.checked || checkbox?.indeterminate));
    if (checkbox) {
      nodo?.setAttribute("aria-selected", String(checkbox.checked));
    }
  };

  const actualizarAncestrosTree = (nodo) => {
    let actual = nodo?.parentElement?.closest(".im-treeview__node");

    while (actual) {
      const checkbox = checkboxTreeDeNodo(actual);
      const descendientes = checkboxesTreeDeGrupo(grupoTreeDeNodo(actual));
      const marcados = descendientes.filter((item) => item.checked).length;
      const indeterminados = descendientes.some((item) => item.indeterminate);

      if (checkbox) {
        checkbox.checked = marcados > 0 && marcados === descendientes.length;
        checkbox.indeterminate = !checkbox.checked && (marcados > 0 || indeterminados);
      }

      actualizarEstadoVisualTree(actual);
      actual = actual.parentElement?.closest(".im-treeview__node");
    }
  };

  const propagarSeleccionTree = (nodo, marcado) => {
    const descendientes = checkboxesTreeDeGrupo(grupoTreeDeNodo(nodo));
    descendientes.forEach((checkbox) => {
      checkbox.checked = marcado;
      checkbox.indeterminate = false;
      checkbox.closest(".im-treeview__node")?.classList.toggle("is-selected", marcado);
    });
  };

  const definirExpansionTree = (nodo, expandido) => {
    const grupo = grupoTreeDeNodo(nodo);
    const toggle = nodo.querySelector(":scope > .im-treeview__item .im-treeview__toggle");

    if (!grupo || !toggle) {
      return;
    }

    grupo.hidden = !expandido;
    toggle.setAttribute("aria-expanded", String(expandido));
  };

  const construirNodoTree = (li, profundidad, selectable) => {
    const grupoHijo = [...li.children].find((child) => child.matches("ul, ol")) || null;
    const esCarpeta = Boolean(grupoHijo) || li.dataset.treeKind === "folder";
    const etiqueta = obtenerTextoTree(li, grupoHijo);
    const meta = li.dataset.treeMeta || "";
    const icono = resolverIconoTree(li, esCarpeta, etiqueta);
    const item = document.createElement("div");
    const contenido = document.createElement("div");
    const nombre = document.createElement("span");
    const iconoNodo = document.createElement("span");

    li.classList.add("im-treeview__node", esCarpeta ? "im-treeview__node--carpeta" : "im-treeview__node--archivo");
    li.setAttribute("role", "treeitem");
    li.setAttribute("aria-level", String(profundidad + 1));

    item.className = "im-treeview__item";
    contenido.className = "im-treeview__contenido";
    nombre.className = "im-treeview__nombre";
    nombre.textContent = etiqueta || "Sin nombre";
    contenido.appendChild(nombre);

    if (meta) {
      const metaNodo = document.createElement("small");
      metaNodo.className = "im-treeview__meta";
      metaNodo.textContent = meta;
      contenido.appendChild(metaNodo);
    }

    if (esCarpeta) {
      const toggle = document.createElement("button");
      const toggleIcono = document.createElement("span");

      toggle.type = "button";
      toggle.className = "im-treeview__toggle";
      toggle.setAttribute("aria-label", `Alternar ${etiqueta || "carpeta"}`);
      toggleIcono.className = "material-symbols-rounded";
      toggleIcono.setAttribute("aria-hidden", "true");
      toggleIcono.textContent = "chevron_right";
      toggle.appendChild(toggleIcono);
      item.appendChild(toggle);
    } else {
      const espacio = document.createElement("span");
      espacio.className = "im-treeview__toggle-espacio";
      espacio.setAttribute("aria-hidden", "true");
      item.appendChild(espacio);
    }

    if (selectable) {
      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.className = "im-treeview__checkbox";
      checkbox.setAttribute("aria-label", `Seleccionar ${etiqueta || "elemento"}`);
      item.appendChild(checkbox);
      item.dataset.treeInteractivo = "true";
    }

    iconoNodo.className = "im-treeview__icono";
    iconoNodo.innerHTML = `<span class="material-symbols-rounded" aria-hidden="true">${icono}</span>`;
    item.append(iconoNodo, contenido);

    li.textContent = "";
    li.appendChild(item);

    if (grupoHijo) {
      grupoHijo.classList.add("im-treeview__group");
      grupoHijo.setAttribute("role", "group");
      li.appendChild(grupoHijo);
      [...grupoHijo.children].forEach((nodoHijo) => {
        if (nodoHijo.matches("li")) {
          construirNodoTree(nodoHijo, profundidad + 1, selectable);
        }
      });

      const expandidoInicial = li.dataset.treeOpen === "true" || profundidad === 0;
      definirExpansionTree(li, expandidoInicial);

      item.querySelector(".im-treeview__toggle")?.addEventListener("click", (evento) => {
        evento.stopPropagation();
        const expandido = evento.currentTarget.getAttribute("aria-expanded") === "true";
        definirExpansionTree(li, !expandido);
      });
    }

    const checkbox = checkboxTreeDeNodo(li);
    if (checkbox) {
      checkbox.addEventListener("change", () => {
        propagarSeleccionTree(li, checkbox.checked);
        actualizarEstadoVisualTree(li);
        actualizarAncestrosTree(li);
      });

      item.addEventListener("click", (evento) => {
        if (evento.target.closest("button, input, a")) {
          return;
        }

        checkbox.checked = !checkbox.checked;
        checkbox.indeterminate = false;
        checkbox.dispatchEvent(new Event("change", { bubbles: true }));
      });
    } else if (esCarpeta) {
      item.dataset.treeInteractivo = "true";
      item.addEventListener("click", (evento) => {
        if (evento.target.closest("button, a")) {
          return;
        }

        const toggle = item.querySelector(".im-treeview__toggle");
        const expandido = toggle?.getAttribute("aria-expanded") === "true";
        definirExpansionTree(li, !expandido);
      });
    }

    actualizarEstadoVisualTree(li);
  };

  document.querySelectorAll("[data-im-tree]").forEach((tree) => {
    if (tree.dataset.imTreeReady === "true") {
      return;
    }

    tree.dataset.imTreeReady = "true";
    tree.classList.add("im-treeview");
    tree.setAttribute("role", "tree");
    const selectable = tree.dataset.imTreeSelectable !== "false";

    [...tree.children].forEach((nodo) => {
      if (nodo.matches("li")) {
        construirNodoTree(nodo, 0, selectable);
      }
    });
  });

  const registrosMenusFlotantes = [];

  const restaurarPanelMenu = (registro) => {
    if (!registro?.esMenuTabla || !registro.panel || registro.panel.parentNode !== document.body) {
      return;
    }

    if (registro.siguienteOriginal?.parentNode === registro.padreOriginal) {
      registro.padreOriginal.insertBefore(registro.panel, registro.siguienteOriginal);
      return;
    }

    registro.padreOriginal?.appendChild(registro.panel);
  };

  const portalizarPanelMenu = (registro) => {
    if (!registro?.esMenuTabla || !registro.panel || registro.panel.parentNode === document.body) {
      return;
    }

    document.body.appendChild(registro.panel);
  };

  const cerrarMenusFlotantes = () => {
    registrosMenusFlotantes.forEach((registro) => {
      registro.panel?.classList.remove("abierto");
      registro.trigger?.setAttribute("aria-expanded", "false");
      registro.menu?.querySelectorAll("[data-im-submenu-panel]").forEach((panel) => panel.classList.remove("abierto"));
      registro.menu?.querySelectorAll("[data-im-submenu-trigger]").forEach((trigger) => trigger.setAttribute("aria-expanded", "false"));
      restaurarPanelMenu(registro);
    });
  };

  const posicionarMenuTabla = (menu, panel) => {
    if (!menu?.classList.contains("im-menu-tabla") || !panel) {
      return;
    }

    const trigger = menu.querySelector("[data-im-menu-trigger]");
    if (!trigger) {
      return;
    }

    const margen = 8;
    const separacion = 8;
    const triggerRect = trigger.getBoundingClientRect();
    const panelRect = panel.getBoundingClientRect();
    const anchoPanel = panelRect.width || 180;
    const altoPanel = panelRect.height || 0;
    const espacioAbajo = window.innerHeight - triggerRect.bottom - separacion - margen;
    const topAbajo = triggerRect.bottom + separacion;
    const topArriba = Math.max(margen, triggerRect.top - altoPanel - separacion);
    const top = espacioAbajo >= altoPanel ? topAbajo : topArriba;
    const leftMax = Math.max(margen, window.innerWidth - anchoPanel - margen);
    const left = Math.min(Math.max(margen, triggerRect.right - anchoPanel), leftMax);

    panel.style.setProperty("--im-menu-tabla-top", `${top}px`);
    panel.style.setProperty("--im-menu-tabla-left", `${left}px`);
  };

  const reposicionarMenusTablaAbiertos = () => {
    registrosMenusFlotantes.forEach((registro) => {
      if (registro.esMenuTabla && registro.panel?.classList.contains("abierto")) {
        posicionarMenuTabla(registro.menu, registro.panel);
      }
    });
  };

  document.querySelectorAll("[data-im-menu]").forEach((menu) => {
    const trigger = menu.querySelector("[data-im-menu-trigger]");
    const panel = menu.querySelector("[data-im-menu-panel]");
    const registro = {
      menu,
      trigger,
      panel,
      esMenuTabla: menu.classList.contains("im-menu-tabla"),
      padreOriginal: panel?.parentNode,
      siguienteOriginal: panel?.nextSibling
    };

    registrosMenusFlotantes.push(registro);

    trigger?.addEventListener("click", (evento) => {
      evento.stopPropagation();
      const abrir = !panel?.classList.contains("abierto");
      cerrarMenusFlotantes();
      if (abrir) {
        portalizarPanelMenu(registro);
      }
      panel?.classList.toggle("abierto", abrir);
      trigger.setAttribute("aria-expanded", String(abrir));
      if (abrir) {
        posicionarMenuTabla(menu, panel);
      }
    });

    menu.querySelectorAll("[data-im-submenu-trigger]").forEach((submenuTrigger) => {
      const submenu = submenuTrigger.nextElementSibling?.matches("[data-im-submenu-panel]")
        ? submenuTrigger.nextElementSibling
        : null;

      submenuTrigger.addEventListener("click", (evento) => {
        evento.stopPropagation();
        const abrir = !submenu?.classList.contains("abierto");
        menu.querySelectorAll("[data-im-submenu-panel]").forEach((panelSubmenu) => panelSubmenu.classList.remove("abierto"));
        menu.querySelectorAll("[data-im-submenu-trigger]").forEach((triggerSubmenu) => triggerSubmenu.setAttribute("aria-expanded", "false"));
        submenu?.classList.toggle("abierto", abrir);
        submenuTrigger.setAttribute("aria-expanded", String(abrir));
      });
    });

    panel?.querySelectorAll("button").forEach((opcion) => {
      if (opcion.matches("[data-im-submenu-trigger]")) {
        return;
      }

      opcion.addEventListener("click", cerrarMenusFlotantes);
    });
  });

  const tooltip = document.createElement("div");
  const selectorTooltip = ".im-tooltip[data-tooltip], .im-tabla-tareas__acciones .im-boton-icono[aria-label]";
  const margenTooltip = 8;
  const separacionTooltip = 14;
  let elementoTooltip = null;

  tooltip.className = "im-tooltip-flotante";
  tooltip.setAttribute("role", "tooltip");
  tooltip.setAttribute("aria-hidden", "true");
  document.body.appendChild(tooltip);

  const textoTooltip = (elemento) => elemento?.dataset.tooltip || elemento?.getAttribute("aria-label") || "";

  const posicionarTooltip = (x, y) => {
    const ancho = tooltip.offsetWidth;
    const alto = tooltip.offsetHeight;
    const maxX = Math.max(margenTooltip, window.innerWidth - ancho - margenTooltip);
    const maxY = Math.max(margenTooltip, window.innerHeight - alto - margenTooltip);
    const izquierda = Math.min(Math.max(x + separacionTooltip, margenTooltip), maxX);
    let arriba = y + separacionTooltip;

    if (arriba + alto + margenTooltip > window.innerHeight) {
      arriba = y - alto - separacionTooltip;
    }

    arriba = Math.min(Math.max(arriba, margenTooltip), maxY);
    tooltip.style.setProperty("--im-tooltip-x", `${izquierda}px`);
    tooltip.style.setProperty("--im-tooltip-y", `${arriba}px`);
  };

  const mostrarTooltip = (elemento, x, y) => {
    const texto = textoTooltip(elemento);
    if (!texto) return;

    elementoTooltip = elemento;
    tooltip.textContent = texto;
    tooltip.classList.add("visible");
    tooltip.setAttribute("aria-hidden", "false");
    posicionarTooltip(x, y);
  };

  const ocultarTooltip = () => {
    elementoTooltip = null;
    tooltip.classList.remove("visible");
    tooltip.setAttribute("aria-hidden", "true");
  };

  document.addEventListener("pointerover", (evento) => {
    const elemento = evento.target.closest(selectorTooltip);
    if (elemento) mostrarTooltip(elemento, evento.clientX, evento.clientY);
  });

  document.addEventListener("pointermove", (evento) => {
    if (elementoTooltip) posicionarTooltip(evento.clientX, evento.clientY);
  });

  document.addEventListener("pointerout", (evento) => {
    if (elementoTooltip && !evento.relatedTarget?.closest?.(selectorTooltip)) ocultarTooltip();
  });

  document.addEventListener("focusin", (evento) => {
    const elemento = evento.target.closest(selectorTooltip);
    if (!elemento) return;

    const limites = elemento.getBoundingClientRect();
    mostrarTooltip(elemento, limites.left + limites.width / 2, limites.bottom);
  });

  document.addEventListener("focusout", (evento) => {
    if (elementoTooltip === evento.target) ocultarTooltip();
  });

  window.addEventListener("blur", ocultarTooltip);
  window.addEventListener("resize", ocultarTooltip);
  window.addEventListener("scroll", ocultarTooltip, true);
  window.addEventListener("resize", reposicionarMenusTablaAbiertos);
  window.addEventListener("scroll", reposicionarMenusTablaAbiertos, true);

  document.addEventListener("click", (evento) => {
    if (!evento.target.closest("[data-im-menu]") && !evento.target.closest("[data-im-menu-panel]")) {
      cerrarMenusFlotantes();
    }
  });

  document.addEventListener("keydown", (evento) => {
    if (evento.key === "Escape") {
      cerrarMenusFlotantes();
      cerrarBottomSheets();
      cerrarDrawer();
      ocultarTooltip();
    }
  });
})();
