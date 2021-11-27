/* global $ instantsearch algoliasearch */

const autocomplete = instantsearch.connectors.connectAutocomplete(
    ({ indices, refine, widgetParams }, isFirstRendering) => {
      const { container } = widgetParams;

      if (isFirstRendering) {
        container.html('<select id="ais-autocomplete"></select>');

        container.find('select').selectize({
          options: [],
          labelField: 'en_title',
          valueField: 'en_title',
          optgroups: indices.map((index, idx) => ({
            $order: idx,
            id: index.indexId,
            name: index.indexId,
          })),
          optgroupField: 'section',
          optgroupLabelField: 'en_title',
          optgroupValueField: 'id',
          highlight: false,
          onType: refine,
          onBlur() {
            refine(this.getValue());
          },
          onChange: refine,
          score() {
            return function() {
              return 1;
            };
          },
          render: {
            option: hit => `
              <div class="hit">
                ${instantsearch.highlight({ attribute: 'en_title', hit })}
              </div>
            `,
          },
        });

        return;
      }

      const [select] = container.find('select');

      select.selectize.clearOptions();
      indices.forEach(index => {
        if (index.hits.length) {
          index.hits.forEach(hit =>
            select.selectize.addOption(
              Object.assign({}, hit, {
                section: index.indexId,
              })
            )
          );
        }
      });

      select.selectize.refreshOptions(select.selectize.isOpen);
    }
  );

  const searchClient = algoliasearch(
    '344AAZSWHX',
    'e6a5071aa5e4ba8161418cb439d413dd'
  );

  const search = instantsearch({
    indexName: 'products_index',
    searchClient,
  });

  search.addWidgets([
    instantsearch.widgets.index({ indexName: 'learning_posts_index' }),

    instantsearch.widgets.configure({
      hitsPerPage: 10,
    }),

    autocomplete({
      container: $('#autocomplete'),
    }),
  ]);

  search.start();
