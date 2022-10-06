<?php

namespace PhpObjects\BaseClass\Props;

trait StaticProps {
	
	public static string $publicStaticProp = 'Public Static Prop';

	protected static int $protectedStaticProp = 1;

	private static float $privateStaticProp = 3.14;

}