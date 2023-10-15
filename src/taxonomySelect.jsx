import { useState } from "react";
import { __ } from "@wordpress/i18n";
import { SelectControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

const TaxonomySelect = (props) => {
  const { getTaxonomies, attributes, setAttributes } = props;
  const { query, taxonomyRelated } = attributes;

  const taxonomies = useSelect(
    (select) => select("core").getTaxonomies({ type: query.postType }),
    [query.postType]
  );

  return (
    <div>
      <SelectControl
        label={__("Taxonomy name", "wp-performance")}
        help={__("Choose the taxonomy to use for relation", "wp-performance")}
        value={taxonomyRelated}
        options={
          taxonomies
            ? [
                ...[{ label: "", value: null }],
                ...taxonomies.map((tax) => ({
                  label: tax.name,
                  value: tax.slug,
                })),
              ]
            : []
        }
        onChange={(value) => setAttributes({ taxonomyRelated: value })}
        __nextHasNoMarginBottom
        size="__unstable-large"
      />
    </div>
  );
};

export default TaxonomySelect;
