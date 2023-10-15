const WP_PERF_VARIATION_NAME = "wp-performance/related";

const addWPPerfVariation = function () {
  if (!wp) {
    return;
  }
  // use window wp global function
  const { Icon } = wp.components;
  const { createElement } = wp.element;
  const getIcon = function () {
    return createElement(Icon, {
      icon: createElement(
        "svg",
        {
          width: "32",
          height: "32",
          viewBox: "0 0 24 24",
        },
        createElement("path", {
          fill: "#747280",
          d: "M22 13v6h-1l-2-2h-8V9H8v2H6V9H5v2H3V9H2V7h1V5h2v2h1V5h2v2h5v8h6l2-2Z",
        })
      ),
    });
  };

  /** create variation block */
  wp.blocks.registerBlockVariation("core/query", {
    name: WP_PERF_VARIATION_NAME,
    title: "Related Loop",
    description: "Displays a related block loop",
    isActive: ({ namespace, query }) => {
      return namespace === WP_PERF_VARIATION_NAME;
    },
    icon: getIcon(),
    attributes: {
      className: "",
      namespace: WP_PERF_VARIATION_NAME,
      displayLayout: { type: "flex", columns: 3 },
      query: {
        perPage: 10,
        pages: 0,
        offset: 0,
        postType: "post",
        order: "desc",
        orderBy: "date",
        author: "",
        search: "",
        exclude: [],
        sticky: "",
        inherit: false,
      },
    },
    scope: ["inserter"],
    innerBlocks: [],
  });
};

document.addEventListener("DOMContentLoaded", () => {
  addWPPerfVariation();
});
