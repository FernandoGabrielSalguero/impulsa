<style>
  .im-cliente-avance {
    display: grid;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .im-cliente-avance__tarjeta {
    display: grid;
    gap: 1rem;
  }

  .im-cliente-avance__resumen {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    align-items: center;
    justify-content: space-between;
  }

  .im-cliente-avance__metricas {
    display: flex;
    flex-wrap: wrap;
    gap: .65rem;
  }

  .im-cliente-avance__barra {
    width: 100%;
    height: .7rem;
    border: 0;
    border-radius: 999px;
    overflow: hidden;
    background: var(--im-color-borde);
    appearance: none;
  }

  .im-cliente-avance__barra::-webkit-progress-bar {
    background: var(--im-color-borde);
    border-radius: 999px;
  }

  .im-cliente-avance__barra::-webkit-progress-value {
    background: var(--im-color-principal);
    border-radius: 999px;
  }

  .im-cliente-avance__barra::-moz-progress-bar {
    background: var(--im-color-principal);
    border-radius: 999px;
  }

  .im-cliente-avance__fases {
    display: grid;
    gap: .9rem;
  }

  .im-cliente-avance__fase {
    border: 1px solid var(--im-color-borde);
    border-radius: 20px;
    padding: 1rem;
    background: var(--im-color-superficie);
    display: grid;
    gap: .85rem;
  }

  .im-cliente-avance__fase-cabecera,
  .im-cliente-avance__objetivo {
    display: flex;
    gap: .75rem;
    align-items: flex-start;
    justify-content: space-between;
  }

  .im-cliente-avance__fase-titulo,
  .im-cliente-avance__objetivo-titulo {
    display: grid;
    gap: .25rem;
  }

  .im-cliente-avance__encabezado-item {
    display: flex;
    gap: .75rem;
    align-items: flex-start;
  }

  .im-cliente-avance__meta,
  .im-cliente-avance__objetivo-meta {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    color: var(--im-color-texto-suave);
    font-size: .92rem;
  }

  .im-cliente-avance__objetivos {
    display: grid;
    gap: .75rem;
  }

  .im-cliente-avance__objetivo {
    padding: .9rem 1rem;
    border-radius: 16px;
    background: var(--im-color-superficie-2);
  }

  .im-cliente-avance__icono {
    color: var(--im-color-texto-suave);
    font-size: 1.15rem;
    line-height: 1;
    margin-top: .2rem;
  }

  .im-cliente-avance__vacio {
    border: 1px dashed var(--im-color-borde);
    border-radius: 16px;
    padding: 1rem;
    color: var(--im-color-texto-suave);
    background: var(--im-color-superficie-2);
  }

  .im-cliente-contrato-barra {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
  }

  .im-cliente-contrato-barra__texto {
    display: grid;
    gap: .2rem;
    flex: 1 1 320px;
  }

  .im-cliente-contrato-barra__acciones {
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-wrap: wrap;
    justify-content: flex-end;
  }
</style>
