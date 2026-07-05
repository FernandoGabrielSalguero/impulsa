(() => {
  const form = document.querySelector("[data-pagina-web-form]");
  const dataNode = document.getElementById("pagina-web-form-data");

  if (!form || !dataNode) {
    return;
  }

  let data = {};
  try {
    data = JSON.parse(dataNode.textContent || "{}");
  } catch {
    data = {};
  }

  const rubro = form.querySelector("[data-pagina-web-rubro]");
  const subcategoria = form.querySelector("[data-pagina-web-subcategoria]");
  const pais = form.querySelector("[data-pagina-web-pais]");
  const provincia = form.querySelector("[data-pagina-web-provincia]");
  const localidad = form.querySelector("[data-pagina-web-localidad]");
  const espacioFisico = form.querySelector("[data-pagina-web-espacio-fisico]");
  const direccionCampos = [...form.querySelectorAll("[data-pagina-web-direccion]")];
  const ubicaciones = data.ubicaciones || {};
  const seleccion = data.seleccion || {};
  const subcategoriaOpciones = subcategoria
    ? [...subcategoria.querySelectorAll("option[data-categoria-id]")].map((option) => option.cloneNode(true))
    : [];

  const crearOpcion = (value, label) => {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = label;
    return option;
  };

  const resetSelect = (select, placeholder, disabled = true) => {
    if (!select) {
      return;
    }

    select.replaceChildren(crearOpcion("", placeholder));
    select.disabled = disabled;
  };

  const renderSubcategorias = (resetear = false) => {
    if (!rubro || !subcategoria) {
      return;
    }

    const categoriaId = rubro.value;
    const valorActual = resetear ? "" : (subcategoria.value || String(seleccion.rubro_subcategoria_id || ""));
    resetSelect(
      subcategoria,
      categoriaId ? "Seleccionar subcategoria" : "Selecciona primero un rubro",
      !categoriaId
    );

    if (!categoriaId) {
      return;
    }

    subcategoriaOpciones
      .filter((option) => option.dataset.categoriaId === categoriaId)
      .forEach((option) => subcategoria.append(option.cloneNode(true)));

    if ([...subcategoria.options].some((option) => option.value === valorActual)) {
      subcategoria.value = valorActual;
    }
  };

  const renderProvincias = (resetear = false) => {
    if (!pais || !provincia || !localidad) {
      return;
    }

    const paisSeleccionado = pais.value;
    const valorActual = resetear ? "" : (provincia.value || seleccion.provincia || "");
    resetSelect(
      provincia,
      paisSeleccionado ? "Seleccionar provincia" : "Selecciona primero un pais",
      !paisSeleccionado
    );
    resetSelect(localidad, "Selecciona primero una provincia", true);

    if (!paisSeleccionado || !ubicaciones[paisSeleccionado]) {
      return;
    }

    Object.keys(ubicaciones[paisSeleccionado]).forEach((nombreProvincia) => {
      provincia.append(crearOpcion(nombreProvincia, nombreProvincia));
    });

    if ([...provincia.options].some((option) => option.value === valorActual)) {
      provincia.value = valorActual;
    }

    renderLocalidades(resetear);
  };

  const renderLocalidades = (resetear = false) => {
    if (!pais || !provincia || !localidad) {
      return;
    }

    const paisSeleccionado = pais.value;
    const provinciaSeleccionada = provincia.value;
    const valorActual = resetear ? "" : (localidad.value || seleccion.localidad || "");
    resetSelect(
      localidad,
      provinciaSeleccionada ? "Seleccionar localidad" : "Selecciona primero una provincia",
      !provinciaSeleccionada
    );

    const localidades = ubicaciones[paisSeleccionado]?.[provinciaSeleccionada] || [];
    localidades.forEach((nombreLocalidad) => {
      localidad.append(crearOpcion(nombreLocalidad, nombreLocalidad));
    });

    if ([...localidad.options].some((option) => option.value === valorActual)) {
      localidad.value = valorActual;
    }
  };

  const renderEspacioFisico = () => {
    if (!espacioFisico) {
      return;
    }

    const tieneEspacio = espacioFisico.value === "1";
    direccionCampos.forEach((campo) => {
      const input = campo.querySelector("input");
      campo.hidden = !tieneEspacio;
      if (input) {
        input.required = tieneEspacio;
        input.disabled = !tieneEspacio;
        if (!tieneEspacio) {
          input.value = "";
        }
      }
    });
  };

  rubro?.addEventListener("change", () => renderSubcategorias(true));
  pais?.addEventListener("change", () => renderProvincias(true));
  provincia?.addEventListener("change", () => renderLocalidades(true));
  espacioFisico?.addEventListener("change", renderEspacioFisico);

  renderSubcategorias(false);
  renderProvincias(false);
  renderEspacioFisico();
})();
