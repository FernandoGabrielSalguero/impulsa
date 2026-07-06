export function createLogger(integrationName, publicKey) {
  const rows = [];

  function set(feature, status, items, detail) {
    rows.push({
      feature,
      status,
      items: items ?? '—',
      detail: detail ?? '',
    });
  }

  function printTable() {
    const title = `[Impulsa SDK v1] Proyecto: ${integrationName || '—'} | ${publicKey || 'sin clave'}`;
    console.log(title);

    if (typeof console.table === 'function') {
      console.table(rows);
      return;
    }

    rows.forEach((row) => {
      console.log(`${row.feature}: ${row.status} | items=${row.items} | ${row.detail}`);
    });
  }

  return { set, printTable };
}
