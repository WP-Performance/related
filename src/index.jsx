import { InspectorControls } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { Panel, PanelBody, CardDivider } from "@wordpress/components";
import TaxonomySelectwithSelect from "./taxonomySelect";

function isRelatedBlock(blockName) {
  return blockName === "wp-performance/related";
}

export const withTaxonomyControls = (BlockEdit) => (props) => {
  return isRelatedBlock(props.attributes.namespace) ? (
    <>
      <BlockEdit {...props} />
      <InspectorControls>
        <PanelBody title={__("Related Taxonomy", "wp-performance")}>
          <TaxonomySelectwithSelect {...props} />
        </PanelBody>
      </InspectorControls>
    </>
  ) : (
    <BlockEdit {...props} />
  );
};

wp.hooks.addFilter("editor.BlockEdit", "core/query", withTaxonomyControls);

/**
 * add taxonomyRelated attribute to core/query block
 * @param {*} settings
 * @returns {*}
 */
const addRelatedAttribute = (settings) => {
  if (settings.name != "core/query") {
    return settings;
  }
  settings.attributes = {
    ...settings.attributes,
    taxonomyRelated: {
      type: "string",
      default: "",
    },
  };

  return settings;
};

wp.hooks.addFilter(
  "blocks.registerBlockType",
  "wp-performance/related-attributes",
  addRelatedAttribute
);
