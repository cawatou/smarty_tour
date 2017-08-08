<?php

interface DomainObjectTree
{
    /**
     * Fetches a/the root node.
     *
     * @param int|null $root_id
     */
    public function fetchRoot($root_id = null);

    /**
     * Fetches the complete tree, returning the root node of the tree
     *
     * @param mixed $root_id the root id of the tree (or null if model doesn't
     *                       support multiple trees
     * @param int   $depth   the depth to retrieve or null for unlimited
     * @return DomainObjectNode|bool $root
     */
    public function fetchTree($root_id = null, $depth = null);

    /**
     * Fetches the complete tree, returning a flat array of node wrappers with
     * parent, children, ancestors and descendants pre-populated.
     *
     * @param mixed $root_id the root id of the tree (or null if model doesn't
     *                       support multiple trees
     * @param int   $depth   the depth to retrieve or null for unlimited
     *
     * @return DomainObjectNode[]|bool
     */
    public function fetchTreeAsArray($root_id = null, $depth = null);

    /**
     * Fetches a branch of a tree, returning the starting node of the branch.
     * All children and descendants are pre-populated.
     *
     * @param mixed $pk    the primary key used to locate the node to traverse
     *                     the tree from
     * @param int   $depth the depth to retrieve or null for unlimited
     *
     * @return DomainObjectNode $root_branch
     */
    public function fetchBranch($pk, $depth = null);

    /**
     * Fetches a branch of a tree, returning a flat array of node wrappers with
     * parent, children, ancestors and descendants pre-populated.
     *
     * @param mixed $pk    the primary key used to locate the node to traverse
     *                     the tree from
     * @param int   $depth the depth to retrieve or null for unlimited
     *
     * @return DomainObjectNode[]|bool
     */
    public function fetchBranchAsArray($pk, $depth = null);

    /**
     * Creates a new root node
     *
     * @param DomainObjectModel $node
     * @return DomainObjectNode
     */
    public function createRoot(DomainObjectModel $node);

    /**
     * Wraps the node using the DomainObjectNode class
     *
     * @param DomainObjectModel $node
     * @return DomainObjectNode
     */
    public function wrapNode(DomainObjectModel $node);

    /**
     * Resets the manager. Clears DomainObjectNode caches.
     */
    public function reset();

    /**
     * Returns the DomainObjectManager associated with this Manager
     *
     * @return DomainObjectManager
     */
    public function getDomainObjectManager();

    /**
     * @return Doctrine_Tree
     */
    public function getTreeManager();

    /**
     * @param DomainObjectManager $dom
     */
    public function setDomainObjectManager(DomainObjectManager $dom);

    /**
     * @param Doctrine_Tree $nsm
     */
    public function setTreeManager(Doctrine_Tree $nsm);
}
