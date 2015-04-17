<?php

namespace ValueParsers\Test;

use ValueParsers\CalendarModelParser;
use ValueParsers\IsoTimestampParser;
use ValueParsers\ParserOptions;

/**
 * @covers ValueParsers\CalendarModelParser
 *
 * @group DataValue
 * @group DataValueExtensions
 *
 * @author Adam Shorland
 * @author Thiemo Mättig
 */
class CalendarModelParserTest extends ValueParserTestBase {

	/**
	 * @deprecated since 0.3, just use getInstance.
	 */
	protected function getParserClass() {
		throw new \LogicException( 'Should not be called, use getInstance' );
	}

	/**
	 * @see ValueParserTestBase::getInstance
	 *
	 * @return CalendarModelParser
	 */
	protected function getInstance() {
		$options = new ParserOptions();

		$options->setOption( CalendarModelParser::OPT_CALENDAR_MODEL_URIS, array(
			'Localized' => 'Unlocalized',
		) );

		return new CalendarModelParser( $options );
	}

	/**
	 * @see ValueParserTestBase::requireDataValue
	 *
	 * @return bool
	 */
	protected function requireDataValue() {
		return false;
	}

	/**
	 * @see ValueParserTestBase::validInputProvider
	 */
	public function validInputProvider() {
		return array(
			array( '', IsoTimestampParser::CALENDAR_GREGORIAN ),
			array( 'Gregorian', IsoTimestampParser::CALENDAR_GREGORIAN ),
			array( 'Julian', IsoTimestampParser::CALENDAR_JULIAN ),

			// White space
			array( ' ', IsoTimestampParser::CALENDAR_GREGORIAN ),
			array( ' Gregorian ', IsoTimestampParser::CALENDAR_GREGORIAN ),
			array( ' Julian ', IsoTimestampParser::CALENDAR_JULIAN ),

			// Capitalization
			array( 'GreGOrIAN', IsoTimestampParser::CALENDAR_GREGORIAN ),
			array( 'julian', IsoTimestampParser::CALENDAR_JULIAN ),
			array( 'JULIAN', IsoTimestampParser::CALENDAR_JULIAN ),

			// See https://en.wikipedia.org/wiki/Gregorian_calendar
			array( 'Western', IsoTimestampParser::CALENDAR_GREGORIAN ),
			array( 'Christian', IsoTimestampParser::CALENDAR_GREGORIAN ),

			// URIs
			array( 'http://www.wikidata.org/entity/Q1985727', IsoTimestampParser::CALENDAR_GREGORIAN ),
			array( 'http://www.wikidata.org/entity/Q1985786', IsoTimestampParser::CALENDAR_JULIAN ),

			// Via OPT_CALENDAR_MODEL_URIS
			array( 'Localized', 'Unlocalized' ),
		);
	}

	/**
	 * @see ValueParserTestBase::invalidInputProvider
	 */
	public function invalidInputProvider() {
		return array(
			array( null ),
			array( true ),
			array( 1 ),
			array( 'foobar' ),

			// Do not confuse Greece with Gregorian
			array( 'gr' ),
			array( 'gre' ),

			// Do not confuse July with Julian
			array( 'Jul' ),
			array( 'J' ),

			// Strict comparison for URIs and strings given via OPT_CALENDAR_MODEL_URIS
			array( 'http://www.wikidata.org/entity/Q1985727 ' ),
			array( 'Localized ' ),
			array( 'localized' ),
			array( 'LOCALIZED' ),
		);
	}

}
