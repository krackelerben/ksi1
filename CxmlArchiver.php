<?php

/**
 * CxmlArchiver is responsible for saving cXML documents as CxmlArchive objects, and providing access to them as a
 * collection.
 *
 * @author Brian Newsham
 *
 */
class CxmlArchiver
{

    /**
     * Flag indicating that the archiver is initialized properly.
     *
     * @var bool
     */
    private $isInitialized;

    /**
     * ID of the end point where documents are being sent to and from.
     *
     * @var int
     */
    protected $cxml_endpoint_id;

    /**
     * ID of the user that is sending the documents.
     *
     * @var int
     */
    protected $user_id;

    /**
     * The BuyerCookie string.
     *
     * @var string
     */
    protected $buyer_cookie;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->isInitialized = false;
    }

    /**
     * Initialize the archiver.
     *
     * @param CxmlEndpoint $endpoint
     * @param KWebUser $user
     * @param string $buyer_cookie
     */
    public function initialize($endpoint, $user, $buyer_cookie)
    {
        $this->cxml_endpoint_id = $endpoint->id;
        $this->user_id = $user->id;
        $this->buyer_cookie = $buyer_cookie;
        $this->isInitialized = true;
    }

    /**
     * Adds a document to the archive.
     *
     * @param CxmlDocument $doc
     * @return int The ID of the CxmlArchive record.
     */
    public function addDocument($doc)
    {
        if (! $this->isInitialized) {
            throw new Exception('CxmlArchiver must be initialized before calling addDocument');
        }
        $cxml_archive = new CxmlArchive();
        $cxml_archive->cxml_endpoint_id = $this->cxml_endpoint_id;
        $cxml_archive->user_id = $this->user_id;
        $cxml_archive->buyer_cookie = $this->buyer_cookie;
        $cxml_archive->cxml_class = get_class($doc);
        $cxml_archive->cxml_doc = XmlHelper::encodeNumericEntity($doc->__toString());
        $cxml_archive->save();

        return $cxml_archive->id;
    }

    /**
     * Get an array of CxmlArchive objects that have the given buyer_cookie.
     *
     * @param string $buyer_cookie
     */
    public function findBuyerCookie($buyer_cookie)
    {
        return CxmlArchive::model()->findAllByAttributes(
            array(
                'buyer_cookie' => $buyer_cookie
            )
        );
    }

    /**
     * Find a CxmlArchive by primary key, and buyer_cookie as a security measure.
     *
     * @param string $buyer_cookie
     *            Buyer cookie value.
     * @param int $id
     *            ID of the CxmlArchive record.
     * @return CxmlArchive|null
     */
    public function findRecentPoom($buyer_cookie, $id)
    {
        return CxmlArchive::model()->findByAttributes(
            array(
                'id' => $id,
                'buyer_cookie' => $buyer_cookie
            )
        );
    }
}
