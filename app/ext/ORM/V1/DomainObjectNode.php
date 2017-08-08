<?php

interface DomainObjectNode
{
    /**
     * Gets first child or null
     *
     * @return DomainObjectNode|null
     */
    public function getFirstChild();

    /**
     * Gets last child or null
     *
     * @return DomainObjectNode|null
     */
    public function getLastChild();

    /**
     * Gets descendants for this node
     *
     * @param int $depth or null for unlimited depth
     * @return DomainObjectNode[]
     */
    public function getDescendants($depth = null);

    /**
     * Gets children of this node (direct descendants only)
     *
     * @return DomainObjectNode[]
     */
    public function getChildren();

    /**
     * Gets parent DomainObjectNode or null
     *
     * @return DomainObjectNode
     */
    public function getParent();

    /**
     * Gets ancestors for node
     *
     * @return DomainObjectNode[]
     */
    public function getAncestors();

    /**
     * Gets the level of this node
     *
     * @return int
     */
    public function getLevel();

    /**
     * gets path to node from root, uses Node::toString() method to get node
     * names
     *
     * @param string $separator   path separator
     * @param bool   $includeNode whether or not to include node at end of path
     *
     * @return string string representation of path
     */
    public function getPath($separator = ' > ', $includeNode = false);

    /**
     * Gets number of children (direct descendants)
     *
     * @return int
     */
    public function getNumberChildren();

    /**
     * Gets number of descendants (children and their children ...)
     *
     * @return int
     */
    public function getNumberDescendants();

    /**
     * Gets siblings for node
     *
     * @param bool $includeNode whether to include this node in the list of
     *                          sibling nodes (default: false)
     *
     * @return DomainObjectNode[]
     */
    public function getSiblings($includeNode = false);

    /**
     * Gets prev sibling or null
     *
     * @return DomainObjectNode|null
     */
    public function getPrevSibling();

    /**
     * Gets next sibling or null
     *
     * @return DomainObjectNode|null
     */
    public function getNextSibling();

    /**
     * Test if node has previous sibling
     *
     * @return bool
     */
    public function hasPrevSibling();

    /**
     * Test if node has next sibling
     *
     * @return bool
     */
    public function hasNextSibling();

    //
    // Tree Modification Methods
    //

    /**
     * Inserts node as parent of given node
     *
     * @param DomainObjectNode $node
     */
    public function insertAsParentOf(DomainObjectNode $node);

    /**
     * Inserts node as previous sibling of given node
     *
     * @param DomainObjectNode $node
     */
    public function insertAsPrevSiblingOf(DomainObjectNode $node);

    /**
     * Inserts node as next sibling of given node
     *
     * @param DomainObjectNode $node
     */
    public function insertAsNextSiblingOf(DomainObjectNode $node);

    /**
     * Inserts node as first child of given node
     *
     * @param DomainObjectNode $node
     */
    public function insertAsFirstChildOf(DomainObjectNode $node);

    /**
     * Inserts node as last child of given node
     *
     * @param DomainObjectNode $node
     */
    public function insertAsLastChildOf(DomainObjectNode $node);

    /**
     * Moves node as previous sibling of the given node
     *
     * @param DomainObjectNode $node
     */
    public function moveAsPrevSiblingOf(DomainObjectNode $node);

    /**
     * Moves node as next sibling of the given node
     *
     * @param DomainObjectNode $node
     */
    public function moveAsNextSiblingOf(DomainObjectNode $node);

    /**
     * Moves node as first child of the given node
     *
     * @param DomainObjectNode $node
     */
    public function moveAsFirstChildOf(DomainObjectNode $node);

    /**
     * Moves node as last child of the given node
     *
     * @param DomainObjectNode $node
     */
    public function moveAsLastChildOf(DomainObjectNode $node);

    /**
     * Makes this node a root node.
     *
     * @param int $new_root_id
     */
    public function makeRoot($new_root_id);

    /**
     * adds given node as the last child of this entity
     *
     * @param DomainObjectModel|DomainObjectNode $node
     * @return DomainObjectNode
     */
    public function addChild($node);

    /**
     * Deletes this node and it's decendants
     *
     */
    public function delete();

    /**
     * Returns the wrapped node
     *
     * @return DomainObjectModel
     */
    public function getNode();


    /**
     * Test if node has children
     *
     * @return bool
     */
    public function hasChildren();

    /**
     * Test if node has parent
     *
     * @return bool
     */
    public function hasParent();

    /**
     * Determines if node is root
     *
     * @return bool
     */
    public function isRoot();

    /**
     * Determines if node is leaf
     *
     * @return bool
     */
    public function isLeaf();

    /**
     * Determines if node is valid
     *
     * @return bool
     */
    public function isValidNode();

    /**
     * ReMoves all cached ancestor/descendant entites
     */
    public function invalidate();

    /**
     * Determines if this node is a child of the given node
     *
     * @param DomainObjectNode $node
     * @return bool
     */
    public function isDescendantOf(DomainObjectNode $node);

    /**
     * Determines if this node is an ancestor of the given node
     *
     * @param DomainObjectNode $node
     * @return bool
     */
    public function isAncestorOf(DomainObjectNode $node);

    /**
     * determines if this node is equal to the given node
     *
     * @param DomainObjectNode $node
     * @return bool
     */
    public function isEqualTo(DomainObjectNode $node);

    /**
     * Returns the DomainObjectTree_NestedSet Tree
     *
     * @return DomainObjectTree_NestedSet
     */
    public function getTree();

    /**
     * @return DomainObjectModel
     */
    public function getModel();

    /**
     * @return string
     */
    public function getLeftFieldName();

    /**
     * @return string
     */
    public function getRightFieldName();

    /**
     * @return string
     */
    public function getRootFieldName();

    /**
     * @return bool
     */
    public function hasManyRoots();

    //
    // Node Interface Methods
    //

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return int
     */
    public function getLeftValue();
    /**
     * @param int $lft
     */
    public function setLeftValue($lft);

    /**
     * @return int
     */
    public function getRightValue();

    /**
     * @param int $rgt
     */
    public function setRightValue($rgt);

    /**
     * @return int|mixed
     */
    public function getRootValue();

    /**
     * @param int $root
     */
    public function setRootValue($root);

    /**
     * @return string
     */
    public function __toString();
}