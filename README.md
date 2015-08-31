# API-Challenge-2.0-ItemSet
Website for player to share their builds. Build are saved and can be shared with an URL

Website : https://itemset.guillaumepierson.fr

## How to use

You have two choices, create within the site, or upload an json file.

Creating within the site, give you more options than uploading such as the champion specified,
this will help player to know on which champion, this item set is was created.

### Creating within the site

When crating within the site, you will see that the interface is nearly the same that in the launcher or,
in-game. In this interface, there is a search bar, in this search bar, you can search the name of the item,
or the tags. You have checkbox to restrict the items. You can to select multiples category to restricts even more.
And thee are button group that give all items that are in the following checkbox.

## How this works

There are models representing item set, they have almost the same architecture, so its like translating it.
Internally I use SplInt, SplBool to force check the type, they are not necessary but give more protection.
SplString is not used, because retrieving a value with Twig does not escape it, so i use the regular string.

All items or summoner spells are verified that they exist, the website, will check the list using the RIOT API.
If they don't exist the summoner spell or item is ignored.

During programming, i was not able to check mode and map with the RIOT API, so I have used the SplEnum to Enum the list,
I Riot add a map, I will had to add it the the Enum, it would be quickly.